<?php

namespace Tests\Feature;

use App\Livewire\Settings\LogIndex;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use Tests\TestCase;

class LogPageTest extends TestCase
{
    use RefreshDatabase;

    private string $directory;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = sys_get_temp_dir().'/log-page-test-'.bin2hex(random_bytes(4));
        File::makeDirectory($this->directory);
        File::put($this->directory.'/app.log', "[2026-01-01 00:00:00] local.INFO: Catatan kecil yang terbaca\n");

        // Newer than app.log and over the limit, like a server log nobody rotates.
        $big = fopen($this->directory.'/huge.log', 'w');
        fwrite($big, "[2026-01-01 00:00:00] local.INFO: Baris di file raksasa\n");
        ftruncate($big, LogIndex::MAX_READABLE_BYTES + 1);
        fclose($big);
        touch($this->directory.'/app.log', time() - 3600);
        // A small file, but older: the page must not start here.
        File::put($this->directory.'/aaa-old.log', "[2025-01-01 00:00:00] local.INFO: Catatan lama\n");
        touch($this->directory.'/aaa-old.log', time() - 86400);

        config(['log-viewer.include_files' => [$this->directory.'/*.log'], 'log-viewer.exclude_files' => []]);

        $role = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin']);
        $this->admin = User::factory()->create(['role_id' => $role->id]);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->directory);

        parent::tearDown();
    }

    private function identifier(string $name): string
    {
        return \Opcodes\LogViewer\Facades\LogViewer::getFiles()->first(fn ($file) => $file->name === $name)->identifier;
    }

    public function test_the_page_opens_a_file_that_is_small_enough_to_read(): void
    {
        Livewire::actingAs($this->admin)->test(LogIndex::class)
            ->assertSet('selectedFileIdentifier', $this->identifier('app.log'))
            ->assertSee('Catatan kecil yang terbaca');
    }

    public function test_a_log_outside_the_application_is_emptied_but_kept(): void
    {
        $path = $this->directory.'/app.log';

        Livewire::actingAs($this->admin)->test(LogIndex::class)
            ->call('delete', $this->identifier('app.log'))
            ->assertDispatched('notify');

        // Server logs may be held open by the server process: empty them, never unlink.
        $this->assertFileExists($path);
        clearstatcache();
        $this->assertSame(0, filesize($path));
    }

    public function test_an_application_log_is_deleted(): void
    {
        $directory = storage_path('logs/_log-page-test-'.bin2hex(random_bytes(4)));
        File::makeDirectory($directory);
        File::put($directory.'/old-app.log', "[2026-01-01 00:00:00] local.INFO: Hapus saya\n");
        config(['log-viewer.include_files' => [$directory.'/*.log']]);

        try {
            Livewire::actingAs($this->admin)->test(LogIndex::class)
                ->call('delete', $this->identifier('old-app.log'))
                ->assertSet('selectedFileIdentifier', '')
                ->assertOk();

            $this->assertFileDoesNotExist($directory.'/old-app.log');
        } finally {
            File::deleteDirectory($directory);
        }
    }

    public function test_only_a_super_admin_may_delete_logs(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $other = User::factory()->create(['role_id' => $role->id]);

        Livewire::actingAs($other)->test(LogIndex::class)
            ->call('delete', $this->identifier('app.log'))
            ->assertForbidden();

        clearstatcache();
        $this->assertNotSame(0, filesize($this->directory.'/app.log'));
    }

    public function test_the_newest_entries_come_first(): void
    {
        File::put($this->directory.'/app.log', "[2026-01-01 00:00:00] local.INFO: Entri pertama\n[2026-01-02 00:00:00] local.INFO: Entri terakhir\n");

        Livewire::actingAs($this->admin)->test(LogIndex::class)
            ->set('selectedFileIdentifier', $this->identifier('app.log'))
            ->assertSeeInOrder(['Entri terakhir', 'Entri pertama']);
    }

    public function test_a_too_large_file_is_explained_instead_of_being_read(): void
    {
        Livewire::actingAs($this->admin)->test(LogIndex::class)
            ->set('selectedFileIdentifier', $this->identifier('huge.log'))
            ->assertOk()
            ->assertSee('terlalu besar untuk ditampilkan')
            ->assertSee('Unduh');
    }
}
