<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Http;

class ResetPasswordQueued extends ResetPasswordNotification implements ShouldQueue
{
    use Queueable;

    public string $ipAddress;

    public function __construct(#[\SensitiveParameter] $token)
    {
        parent::__construct($token);

        $this->queue = 'default';

        // Capture IP at request time (before job is queued)
        $this->ipAddress = request()->ip() ?? 'Unknown';
    }

    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('auth.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password - '.config('app.name'))
            ->view('emails.reset-password', [
                'userName'  => $notifiable->name,
                'userEmail' => $notifiable->email,
                'resetUrl'  => $resetUrl,
                'subject'   => 'Reset Password',
                'ipAddress' => $this->ipAddress,
                'location'  => $this->resolveLocation($this->ipAddress),
            ]);
    }

    private function resolveLocation(string $ip): string
    {
        // Skip lookup for local/private IPs
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
            // Silent fail — lokasi tidak kritis
        }

        return 'Tidak diketahui';
    }
}
