<?php

namespace Tests\Feature;

use App\Livewire\Auth\ChangePassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Users\UserIndex;
use App\Models\Role;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use Tests\TestCase;

class TemporaryPasswordTest extends TestCase
{
    use RefreshDatabase;

    private const NEW_PASSWORD = 'Sandi-Baru-9!kuat';

    private Role $role;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        // The password rule asks an outside service whether a password has leaked; keep tests offline.
        Http::fake(['api.pwnedpasswords.com/*' => Http::response('')]);

        $this->role = Role::create(['name' => 'User', 'slug' => 'user']);
    }

    private function invite(string $email = 'baru@contoh.test'): array
    {
        $user = app(UserRepositoryInterface::class)
            ->createInvited(['name' => 'Pengguna Baru', 'email' => $email], [$this->role->id], $this->role->id);

        return [$user, Notification::sent($user, VerifyEmailNotification::class)->first()->password];
    }

    private function verify(User $user)
    {
        return $this->get((new VerifyEmailNotification)->toMail($user)->viewData['verificationUrl']);
    }

    public function test_the_verification_page_asks_to_change_the_temporary_password(): void
    {
        [$user] = $this->invite();

        $this->assertTrue($user->must_change_password);

        $this->verify($user)
            ->assertSee('Email berhasil diverifikasi')
            ->assertSee('kata sandi sementara');
    }

    public function test_a_self_registered_user_is_not_asked_to_change_anything(): void
    {
        $user = app(UserRepositoryInterface::class)->register([
            'name' => 'Daftar Sendiri', 'email' => 'sendiri@contoh.test', 'password' => self::NEW_PASSWORD,
        ]);

        $this->assertFalse($user->fresh()->must_change_password);
        $this->verify($user)->assertDontSee('kata sandi sementara');

        $this->actingAs($user->fresh())->get(route('dashboard'))->assertDontSee('masih memakai kata sandi sementara');
    }

    public function test_the_reminder_shows_after_signing_in_until_the_password_is_changed(): void
    {
        [$user, $temporary] = $this->invite();
        $this->verify($user);
        $user = $user->fresh();

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('masih memakai kata sandi sementara')
            ->assertSee(route('password.change'), false);

        Livewire::actingAs($user)->test(ChangePassword::class)
            ->set('current_password', $temporary)
            ->set('password', self::NEW_PASSWORD)
            ->set('password_confirmation', self::NEW_PASSWORD)
            ->call('changePassword')
            ->assertHasNoErrors()
            ->assertDispatched('password-changed');

        $this->assertFalse($user->fresh()->must_change_password);
        $this->actingAs($user->fresh())->get(route('dashboard'))->assertDontSee('masih memakai kata sandi sementara');
    }

    public function test_resending_the_verification_to_an_invited_user_includes_a_new_temporary_password(): void
    {
        [$user, $first] = $this->invite();
        $admin = User::factory()->create(['role_id' => $this->role->id]);

        foreach (['resendActivation', 'bulkResendActivation'] as $action) {
            Notification::fake();
            $component = Livewire::actingAs($admin)->test(UserIndex::class);

            $action === 'resendActivation'
                ? $component->call($action, $user->uuid)
                : $component->set('selectedUsers', [$user->uuid])->call($action);

            $sent = Notification::sent($user, VerifyEmailNotification::class)->first()->password;

            $this->assertNotNull($sent, "{$action} must mail a password");
            $this->assertNotSame($first, $sent);
            $this->assertTrue(Hash::check($sent, $user->fresh()->password), "{$action} must mail the password that works");
        }
    }

    public function test_the_temporary_password_is_short_and_easy_to_type(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            [, $password] = $this->invite("baru{$i}@contoh.test");

            // 8 letters and digits, every kind present, none that look alike (0 O o 1 l I).
            $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{8}$/', $password);
            $this->assertMatchesRegularExpression('/[A-Z]/', $password);
            $this->assertMatchesRegularExpression('/[a-z]/', $password);
            $this->assertMatchesRegularExpression('/[0-9]/', $password);
            $this->assertDoesNotMatchRegularExpression('/[0Oo1lI]/', $password);
        }
    }

    public function test_resending_does_not_touch_a_password_the_user_chose(): void
    {
        $user = app(UserRepositoryInterface::class)->register([
            'name' => 'Daftar Sendiri', 'email' => 'sendiri@contoh.test', 'password' => self::NEW_PASSWORD,
        ]);
        $admin = User::factory()->create(['role_id' => $this->role->id]);
        Notification::fake();

        Livewire::actingAs($admin)->test(UserIndex::class)->call('resendActivation', $user->uuid);

        $this->assertNull(Notification::sent($user, VerifyEmailNotification::class)->first()->password);
        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $user->fresh()->password));
    }

    public function test_resetting_a_forgotten_password_also_ends_the_reminder(): void
    {
        [$user] = $this->invite();

        Livewire::test(ResetPassword::class, ['token' => Password::createToken($user)])
            ->set('email', $user->email)
            ->set('password', self::NEW_PASSWORD)
            ->set('password_confirmation', self::NEW_PASSWORD)
            ->call('resetPassword')
            ->assertHasNoErrors();

        $this->assertFalse($user->fresh()->must_change_password);
    }

    public function test_the_email_to_an_invited_user_does_not_thank_them_for_registering(): void
    {
        [$user, $temporary] = $this->invite();

        $html = (string) (new VerifyEmailNotification($temporary))->toMail($user)->render();

        $this->assertStringContainsString($temporary === '' ? 'x' : e($temporary), $html);
        $this->assertStringContainsString('Akun Anda sudah dibuatkan', $html);
        $this->assertStringContainsString('ubah', $html);
        $this->assertStringNotContainsString('Terima kasih telah mendaftar', $html);
        // The verification email carries no network details of whoever triggered it.
        $this->assertStringNotContainsString('Alamat IP', $html);
        $this->assertStringNotContainsString('Lokasi', $html);
    }
}
