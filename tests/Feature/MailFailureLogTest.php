<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Tests\TestCase;

class MailFailureLogTest extends TestCase
{
    use RefreshDatabase;

    private const TEMPORARY_PASSWORD = 'Rahasia8';

    private ?MessageLogged $logged = null;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // A mail server that refuses, the way Mailtrap does, with an SMTP transcript attached.
        Mail::extend('menolak', fn () => new class extends AbstractTransport
        {
            protected function doSend(SentMessage $message): void
            {
                $exception = new TransportException('Expected response code "354" but got code "550", with message "550 5.7.0 Too many emails per second." for user akun-smtp', 550);
                $exception->appendDebug(implode("\n", [
                    '[2026-01-01T00:00:00.000000+07:00] < 220 smtp.contoh.test ESMTP ready',
                    '[2026-01-01T00:00:00.000000+07:00] > AUTH LOGIN',
                    '[2026-01-01T00:00:00.000000+07:00] < 334 VXNlcm5hbWU6',
                    '[2026-01-01T00:00:00.000000+07:00] > '.base64_encode('akun-smtp'),
                    '[2026-01-01T00:00:00.000000+07:00] > '.base64_encode('sandi-smtp-rahasia'),
                    '[2026-01-01T00:00:00.000000+07:00] > MAIL FROM:<hello@contoh.test>',
                    '[2026-01-01T00:00:00.000000+07:00] < 550 5.7.0 Too many emails per second.',
                ])."\n");

                throw $exception;
            }

            public function __toString(): string
            {
                return 'menolak://';
            }
        });

        config([
            'logging.default' => 'null', // keep test failures out of the developer's log files
            'mail.default' => 'menolak',
            'mail.mailers.menolak' => ['transport' => 'menolak', 'host' => 'smtp.contoh.test', 'port' => 2525, 'username' => 'akun-smtp', 'password' => 'sandi-smtp-rahasia'],
            'app.url' => 'http://alamat-aplikasi.test:5000',
        ]);
        url()->forceRootUrl('http://alamat-aplikasi.test:5000');

        Event::listen(MessageLogged::class, function (MessageLogged $event) {
            if (str_starts_with($event->message, 'Email gagal dikirim')) {
                $this->logged = $event;
            }
        });

        $this->user = User::factory()->unverified()->create(['name' => 'Penerima', 'email' => 'penerima@contoh.test']);
    }

    private function sendAndFail(): array
    {
        try {
            $this->user->notifyNow(new VerifyEmailNotification(self::TEMPORARY_PASSWORD));
            $this->fail('The mail server was supposed to refuse.');
        } catch (TransportException) {
            // expected: the job must still fail so the queue can retry it
        }

        $this->assertNotNull($this->logged, 'A failed email must be logged');

        return $this->logged->context;
    }

    public function test_the_log_shows_what_the_server_answered_and_what_was_sent(): void
    {
        $context = $this->sendAndFail();

        $this->assertSame('error', $this->logged->level);
        $this->assertStringContainsString('VerifyEmailNotification', $this->logged->message);
        $this->assertStringContainsString('550 5.7.0 Too many emails per second', $context['respons']);
        $this->assertSame(550, $context['kode_smtp']);
        $this->assertSame('penerima@contoh.test', $context['kepada']);
        $this->assertSame($this->user->id, $context['user_id']);
        $this->assertStringContainsString('Verifikasi Alamat Email', $context['subjek']);
        $this->assertSame(['mailer' => 'menolak', 'host' => 'smtp.contoh.test', 'port' => 2525], $context['server']);
        $this->assertSame('Penerima', $context['parameter']['userName']);
        // The host of the link is what usually goes wrong (APP_URL), so it stays visible.
        $this->assertStringStartsWith('http://alamat-aplikasi.test:5000/auth/verify-email/', $context['parameter']['verificationUrl']);
        $this->assertStringContainsString('< 550 5.7.0', implode("\n", $context['percakapan_smtp']));
    }

    public function test_nothing_secret_reaches_the_log(): void
    {
        $context = $this->sendAndFail();
        $everything = json_encode($context, JSON_UNESCAPED_SLASHES);

        foreach ([
            'temporary password' => self::TEMPORARY_PASSWORD,
            'link signature' => 'signature=',
            'email hash in the link' => sha1('penerima@contoh.test'),
            'smtp password' => 'sandi-smtp-rahasia',
            'smtp password, base64' => base64_encode('sandi-smtp-rahasia'),
            'smtp user, base64' => base64_encode('akun-smtp'),
            'smtp user' => 'akun-smtp',
        ] as $label => $secret) {
            $this->assertStringNotContainsString($secret, $everything, "The log must not contain the {$label}");
        }
    }
}
