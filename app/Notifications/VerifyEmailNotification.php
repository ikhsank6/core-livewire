<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public ?string $password;

    /** One attempt plus three retries, for example when the mail server rate limits a burst. */
    public int $tries = 4;

    public function __construct(?string $password = null)
    {
        $this->password = $password;
        $this->onQueue('default');
    }

    /** Seconds to wait before each retry. */
    public function backoff(): array
    {
        return [10, 30, 60];
    }

    /** The lifetime of a verification link in words, for the email and the import preview. */
    public static function lifetimeLabel(): string
    {
        $minutes = (int) Config::get('auth.verification.expire');

        return match (true) {
            $minutes >= 1440 && $minutes % 1440 === 0 => ($minutes / 1440).' hari',
            $minutes >= 60 && $minutes % 60 === 0 => ($minutes / 60).' jam',
            default => $minutes.' menit',
        };
    }

    protected function verificationUrl($notifiable): string
    {
        // Signed without the host, so the link survives being opened under another name of the same app.
        return url(URL::temporarySignedRoute(
            'auth.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire')),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ],
            absolute: false
        ));
    }

    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Alamat Email - '.config('app.name'))
            ->view('emails.verify-email', [
                'userName'        => $notifiable->name,
                'userEmail'       => $notifiable->email,
                'password'        => $this->password,
                'verificationUrl' => $verificationUrl,
                'lifetime'        => self::lifetimeLabel(),
                'subject'         => 'Verifikasi Alamat Email',
                'title'           => 'Verifikasi Alamat Email Anda',
                // A password is only sent when someone else created the account.
                'emailContent'    => $this->password
                    ? 'Akun Anda sudah dibuatkan oleh pengelola. Klik tombol di bawah untuk memverifikasi email, masuk dengan kata sandi sementara di bawah, lalu segera ubah kata sandi Anda.'
                    : 'Terima kasih telah mendaftar. Silakan klik tombol di bawah untuk memverifikasi alamat email Anda.',
            ]);
    }
}
