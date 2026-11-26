<?php

namespace Tests\Feature;

use App\Livewire\Layout\NotificationBell;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationBellTest extends TestCase
{
    use RefreshDatabase;

    private Role $role;

    private Role $other;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin']);
        $this->other = Role::create(['name' => 'Anggota', 'slug' => 'anggota']);
        $this->user = User::factory()->create(['role_id' => $this->role->id]);
    }

    private function notify(string $message, ?Role $to = null, ?string $url = null): Notification
    {
        return Notification::create([
            'from_role_id' => $this->role->id, 'to_role_id' => ($to ?? $this->role)->id,
            'message' => $message, 'url' => $url, 'read' => false,
        ]);
    }

    private function bell()
    {
        return Livewire::actingAs($this->user)->test(NotificationBell::class);
    }

    public function test_it_lists_unread_messages_in_indonesian_with_real_buttons(): void
    {
        $this->travel(-4)->hours();
        $this->notify('2 pengguna diimpor oleh Admin.');
        $this->travelBack();

        $html = $this->bell()
            ->assertSee('Notifikasi')
            ->assertSee('1 belum dibaca')
            ->assertSee('2 pengguna diimpor oleh Admin.')
            ->assertSee('4 jam yang lalu')
            ->assertSee('Tandai semua dibaca')
            ->assertSee('Lihat semua notifikasi')
            ->html();

        $this->assertMatchesRegularExpression('/<button[^>]*wire:click="markAsRead\(/', $html, 'Rows must be keyboard reachable');
        $this->assertStringContainsString('aria-label="Notifikasi, 1 belum dibaca"', $html);
        // The sender's role pill only repeated the sentence; nothing loops for attention.
        $this->assertStringNotContainsString('rounded-full bg-blue-50', $html);
        $this->assertStringNotContainsString('infinite', $html);
        $this->assertStringNotContainsString('animate-ping', $html);
    }

    public function test_one_or_all_notifications_can_be_marked_read_for_the_active_role_only(): void
    {
        $first = $this->notify('Pertama');
        $second = $this->notify('Kedua');
        $foreign = $this->notify('Milik role lain', $this->other);

        $this->bell()->call('markAsRead', $first->uuid)->assertDontSee('Pertama')->assertSee('Kedua');
        $this->assertTrue($first->fresh()->read);

        $this->bell()->call('markAllAsRead')->assertSee('Tidak ada notifikasi baru');
        $this->assertTrue($second->fresh()->read);

        $this->bell()->call('markAsRead', $foreign->uuid);
        $this->assertFalse($foreign->fresh()->read, 'Another role\'s notification stays untouched');
    }

    public function test_a_notification_with_a_link_opens_it(): void
    {
        $item = $this->notify('Buka halaman', url: '/profile');

        $this->bell()->call('markAsRead', $item->uuid)->assertRedirect('/profile');
    }

    public function test_the_empty_state_still_leads_to_the_history(): void
    {
        $this->bell()
            ->assertSee('Tidak ada notifikasi baru')
            ->assertSee('Lihat semua notifikasi')
            ->assertDontSee('Tandai semua dibaca');
    }
}
