<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Livewire\Users\UserImport;
use App\Models\Role;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use App\Services\UserImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use OpenSpout\Reader\XLSX\Reader;
use Tests\TestCase;

class UserImportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Notification::fake();

        $superAdmin = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin']);
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'User', 'slug' => 'user']);

        $this->admin = User::factory()->create(['email' => 'admin@contoh.test', 'role_id' => $superAdmin->id]);
    }

    private function csv(string ...$lines): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('pengguna.csv', implode("\n", $lines)."\n");
    }

    private function importer()
    {
        return Livewire::actingAs($this->admin)->test(UserImport::class)->call('open');
    }

    public function test_uploading_a_file_only_previews_it(): void
    {
        $component = $this->importer()->set('file', $this->csv(
            'nama,email,role',
            'Budi Contoh,budi@contoh.test,User',
            'Sari Contoh,sari@contoh.test,"Admin, User"',
        ));

        $rows = $component->get('rows');

        $this->assertCount(2, $rows);
        $this->assertSame([], $rows[0]['errors']);
        $this->assertSame([], $rows[1]['errors']);
        $this->assertSame(1, User::count(), 'Preview must not create users');
    }

    public function test_the_preview_marks_the_rows_that_cannot_be_imported(): void
    {
        $rows = $this->importer()->set('file', $this->csv(
            'nama;email;role',
            ';tanpa-nama@contoh.test;User',
            'Salah Surel;bukan-surel;User',
            'Sudah Ada;ADMIN@contoh.test;User',
            'Kembar Satu;kembar@contoh.test;User',
            'Kembar Dua;Kembar@contoh.test;User',
            'Role Asing;asing@contoh.test;Bendahara',
        ))->get('rows');

        $this->assertSame(2, $rows[0]['line']);
        $this->assertContains('Nama wajib diisi.', $rows[0]['errors']);
        $this->assertContains('Format email tidak valid.', $rows[1]['errors']);
        $this->assertContains('Email sudah terdaftar.', $rows[2]['errors']);
        $this->assertSame([], $rows[3]['errors']);
        $this->assertContains('Email sama dengan baris 5.', $rows[4]['errors']);
        $this->assertContains('Role tidak dikenal: Bendahara.', $rows[5]['errors']);
    }

    public function test_importing_creates_only_the_valid_rows_with_their_roles(): void
    {
        $this->importer()
            ->set('file', $this->csv(
                'nama,email,role',
                'Budi Contoh,budi@contoh.test,User',
                'Sari Contoh,sari@contoh.test,"admin, User"',
                'Role Asing,asing@contoh.test,Bendahara',
            ))
            ->call('import')
            ->assertSet('showModal', false)
            ->assertDispatched('users-imported');

        $this->assertSame(3, User::count());
        $this->assertDatabaseMissing('users', ['email' => 'asing@contoh.test']);

        $sari = User::where('email', 'sari@contoh.test')->firstOrFail();
        $this->assertEqualsCanonicalizing(['Admin', 'User'], $sari->roles->pluck('name')->all());
        $this->assertSame('Admin', $sari->role->name, 'The first role listed is the default');

        // Every imported account is active, unverified, and gets its password with the verification link.
        foreach (['budi@contoh.test', 'sari@contoh.test'] as $email) {
            $user = User::where('email', $email)->firstOrFail();

            $this->assertTrue($user->is_active);
            $this->assertFalse($user->hasVerifiedEmail());

            Notification::assertSentTo($user, VerifyEmailNotification::class,
                fn (VerifyEmailNotification $mail) => Hash::check($mail->password, $user->password));
        }

        $this->assertDatabaseHas('notifications', ['message' => '2 pengguna diimpor oleh '.$this->admin->name.'.']);
    }

    public function test_verifying_the_email_lets_an_imported_user_sign_in(): void
    {
        $this->importer()
            ->set('file', $this->csv('nama,email,role', 'Budi Contoh,budi@contoh.test,User'))
            ->call('import');

        $budi = User::where('email', 'budi@contoh.test')->firstOrFail();
        $password = Notification::sent($budi, VerifyEmailNotification::class)->first()->password;

        auth()->logout();
        $this->get((new VerifyEmailNotification)->toMail($budi)->viewData['verificationUrl'])->assertOk();
        Livewire::test(Login::class)->set('email', $budi->email)->set('password', $password)->call('login');

        $this->assertAuthenticatedAs($budi->fresh());
    }

    public function test_an_email_taken_after_the_preview_is_skipped(): void
    {
        $component = $this->importer()->set('file', $this->csv(
            'nama,email,role',
            'Budi Contoh,budi@contoh.test,User',
        ));

        User::factory()->create(['email' => 'budi@contoh.test']);

        $component->call('import');

        $this->assertSame(2, User::count());
    }

    public function test_a_file_without_the_template_columns_is_refused(): void
    {
        $this->importer()
            ->set('file', $this->csv('nama,alamat', 'Budi,Jalan Contoh'))
            ->assertSet('rows', [])
            ->assertSee('Kolom wajib tidak ditemukan');
    }

    public function test_a_file_with_too_many_rows_is_refused(): void
    {
        $lines = ['nama,email,role'];

        for ($i = 1; $i <= UserImportService::MAX_ROWS + 1; $i++) {
            $lines[] = "Nama {$i},nama{$i}@contoh.test,User";
        }

        $this->importer()
            ->set('file', $this->csv(...$lines))
            ->assertSet('rows', [])
            ->assertSee('Maksimal '.UserImportService::MAX_ROWS.' baris');
    }

    public function test_the_downloaded_template_can_be_read_back(): void
    {
        $this->importer()->call('downloadTemplate')->assertFileDownloaded('template-impor-pengguna.xlsx');

        $path = tempnam(sys_get_temp_dir(), 'tpl');
        $service = app(UserImportService::class);
        $service->writeTemplate($path);

        $sheet = $this->firstSheet($path);

        $this->assertSame(['nama', 'email', 'role', 'keterangan (tidak diimpor)'], $sheet[0]);
        $this->assertSame(['Budi Contoh', 'budi.contoh@example.com', 'User'], array_slice($sheet[1], 0, 3));
        $this->assertSame(['Sari Contoh', 'sari.contoh@example.com', 'User'], array_slice($sheet[2], 0, 3));
        $this->assertStringContainsString('tidak ikut diimpor', implode(' ', array_merge(...$sheet)));

        // An untouched template must not create the example accounts.
        $this->expectExceptionMessage('File hanya berisi baris contoh.');
        $service->read($path, 'xlsx');
    }

    public function test_only_the_known_columns_are_read(): void
    {
        $rows = $this->importer()->set('file', $this->csv(
            'keterangan,role,nama,catatan,email',
            'apa saja,User,Data Asli,abaikan,asli@contoh.test',
            'baris keterangan saja,,,,',
        ))->get('rows');

        $this->assertCount(1, $rows);
        $this->assertSame(['Data Asli', 'asli@contoh.test', 'User'], [$rows[0]['name'], $rows[0]['email'], $rows[0]['role']]);
        $this->assertSame([], $rows[0]['errors']);
    }

    public function test_example_rows_left_in_the_file_are_not_imported(): void
    {
        $rows = $this->importer()->set('file', $this->csv(
            'nama,email,role',
            'Budi Contoh,budi.contoh@example.com,User',
            'Sari Contoh,sari.contoh@example.com,User',
            'Data Asli,asli@contoh.test,User',
        ))->get('rows');

        $this->assertSame(['asli@contoh.test'], array_column($rows, 'email'));
        $this->assertSame(4, $rows[0]['line']);
    }

    public function test_a_renamed_row_that_kept_the_example_email_is_flagged(): void
    {
        $rows = $this->importer()->set('file', $this->csv(
            'nama,email,role',
            'Nama Sungguhan,budi.contoh@example.com,User',
        ))->get('rows');

        $this->assertContains('Email contoh dari template. Ganti dengan email asli.', $rows[0]['errors']);
    }

    private function firstSheet(string $path): array
    {
        $reader = new Reader;
        $reader->open($path);
        $rows = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rows[] = array_map('strval', $row->toArray());
            }

            break;
        }

        $reader->close();

        return $rows;
    }
}
