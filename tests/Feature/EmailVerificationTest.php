<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordQueued;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->unverified()->create(['email' => 'cek@contoh.test']);
    }

    private function link(): string
    {
        return (new VerifyEmailNotification)->toMail($this->user)->viewData['verificationUrl'];
    }

    public function test_the_link_in_the_email_verifies_the_address(): void
    {
        $this->get($this->link())->assertOk()->assertSee('Email berhasil diverifikasi');

        $this->assertTrue($this->user->fresh()->hasVerifiedEmail());
    }

    public function test_a_link_built_by_hand_verifies_nothing(): void
    {
        $this->get('/auth/verify-email/'.$this->user->id.'/'.sha1($this->user->email))
            ->assertOk()
            ->assertSee('Tautan verifikasi tidak berlaku');

        $this->assertFalse($this->user->fresh()->hasVerifiedEmail());
    }

    public function test_the_link_expires_after_three_days(): void
    {
        $link = $this->link();

        $this->travel(3)->days();
        $this->travel(1)->minutes();

        $this->get($link)->assertSee('Tautan verifikasi tidak berlaku');
        $this->assertFalse($this->user->fresh()->hasVerifiedEmail());
    }

    public function test_the_link_still_works_just_before_it_expires(): void
    {
        $link = $this->link();

        $this->travel(2)->days();
        $this->travel(23)->hours();

        $this->get($link)->assertSee('Email berhasil diverifikasi');
    }

    public function test_the_signature_does_not_depend_on_the_host_name(): void
    {
        $link = $this->link();
        $path = parse_url($link, PHP_URL_PATH).'?'.parse_url($link, PHP_URL_QUERY);

        $this->get('http://127.0.0.1:9999'.$path)->assertSee('Email berhasil diverifikasi');
    }

    public function test_the_email_states_how_long_the_link_lasts(): void
    {
        $html = (string) (new VerifyEmailNotification)->toMail($this->user)->render();

        $this->assertStringContainsString('3 hari', $html);
        $this->assertStringNotContainsString('60 menit', $html);
    }

    public function test_mail_is_retried_three_times_with_growing_pauses(): void
    {
        foreach ([new VerifyEmailNotification, new ResetPasswordQueued('token')] as $mail) {
            $this->assertSame(4, $mail->tries, $mail::class.': one attempt plus three retries');
            $this->assertSame([10, 30, 60], $mail->backoff(), $mail::class);
        }
    }
}
