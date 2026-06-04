<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public ?string $password;

    public string $ipAddress;

    public function __construct(?string $password = null)
    {
        $this->password  = $password;
        $this->ipAddress = request()->ip() ?? 'Unknown';
        $this->onQueue('default');
    }

    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'auth.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
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
                'subject'         => 'Verifikasi Alamat Email',
                'title'           => 'Verifikasi Alamat Email Anda',
                'emailContent'    => 'Terima kasih telah mendaftar. Silakan klik tombol di bawah untuk memverifikasi alamat email Anda.',
                'ipAddress'       => $this->ipAddress,
                'location'        => $this->resolveLocation($this->ipAddress),
            ]);
    }

    private function resolveLocation(string $ip): string
    {
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return 'Lokal / Development';
        }

        try {
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}", [
                'fields' => 'status,country,regionName,city',
                'lang'   => 'id',
            ]);

            if ($response->ok()) {
                $data = $response->json();
                if (($data['status'] ?? '') === 'success') {
                    return implode(', ', array_filter([
                        $data['city']       ?? null,
                        $data['regionName'] ?? null,
                        $data['country']    ?? null,
                    ]));
                }
            }
        } catch (\Exception) {
            // Silent fail
        }

        return 'Tidak diketahui';
    }
}
