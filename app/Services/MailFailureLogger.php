<?php

namespace App\Services;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * Logs why an email could not be sent: the server's answer and what was being sent.
 *
 * Passwords, tokens, signed links and SMTP credentials never reach the log; whoever can read
 * logs must not be able to use them. Registered as a singleton in AppServiceProvider.
 */
class MailFailureLogger
{
    private ?MessageSending $lastMessage = null;

    public function remember(MessageSending $event): void
    {
        $this->lastMessage = $event;
    }

    public function forget(MessageSent $event): void
    {
        $this->lastMessage = null;
    }

    public function record(NotificationFailed $event): void
    {
        if ($event->channel !== 'mail') {
            return;
        }

        $exception = $event->data['exception'] ?? null;
        $message = $this->lastMessage?->message;
        $mailer = (string) config('mail.default');

        $context = [
            'respons' => $exception?->getMessage(),
            'kode_smtp' => $exception?->getCode(),
            'exception' => $exception ? $exception::class : null,
            'kepada' => $event->notifiable->email ?? null,
            'user_id' => $event->notifiable->id ?? null,
            'subjek' => $message?->getSubject(),
            'dari' => $message ? collect($message->getFrom())->map->getAddress()->implode(', ') : null,
            'server' => array_filter([
                'mailer' => $mailer,
                'host' => config("mail.mailers.{$mailer}.host"),
                'port' => config("mail.mailers.{$mailer}.port"),
            ]),
            'parameter' => $this->parameters($this->lastMessage?->data ?? []),
            'percakapan_smtp' => $exception instanceof TransportExceptionInterface ? $this->transcript($exception->getDebug()) : [],
        ];

        $this->lastMessage = null;

        Log::error('Email gagal dikirim: '.class_basename($event->notification), $this->hideCredentials($context, $mailer));
    }

    /** The values handed to the email view, with everything usable as a credential masked. */
    private function parameters(array $data): array
    {
        $parameters = [];

        foreach ($data as $key => $value) {
            // Empty values are noise, except a missing password, which is worth seeing.
            $isEmpty = ($value === null || $value === '') && ! preg_match('/password/i', $key);

            if ($key === 'message' || str_starts_with($key, '__') || $isEmpty || ! (is_scalar($value) || $value === null)) {
                continue;
            }

            if (is_string($value) && preg_match('/password|token|secret/i', $key)) {
                $value = '*** ('.strlen($value).' karakter)';
            } elseif (is_string($value) && preg_match('#^https?://#', $value)) {
                $value = $this->maskLink($value);
            }

            $parameters[$key] = $value;
        }

        return $parameters;
    }

    /** Keeps the host and route (a wrong APP_URL shows up there), drops ids, hashes and signatures. */
    private function maskLink(string $url): string
    {
        $parts = parse_url($url);
        $segments = array_slice(array_filter(explode('/', $parts['path'] ?? '')), 0, 2);

        return ($parts['scheme'] ?? 'http').'://'.($parts['host'] ?? '')
            .(isset($parts['port']) ? ':'.$parts['port'] : '')
            .'/'.implode('/', $segments).'/***';
    }

    /**
     * The SMTP conversation without what the client sent during login: those lines are the user name
     * and password, only base64 encoded. Server replies and the envelope commands are kept.
     */
    private function transcript(string $debug): array
    {
        return array_values(array_filter(
            preg_split('/\R/', $debug) ?: [],
            fn (string $line) => preg_match('/\] < |\] > (EHLO|HELO|STARTTLS|MAIL FROM|RCPT TO|DATA|RSET|QUIT|AUTH [A-Z0-9-]+$)/', $line) === 1
        ));
    }

    /** Some servers and drivers repeat the account name in their error text. */
    private function hideCredentials(array $context, string $mailer): array
    {
        $secrets = array_filter([
            (string) config("mail.mailers.{$mailer}.username"),
            (string) config("mail.mailers.{$mailer}.password"),
        ], fn (string $secret) => strlen($secret) >= 4);

        array_walk_recursive($context, function (&$value) use ($secrets) {
            if (is_string($value)) {
                $value = str_replace($secrets, '***', $value);
            }
        });

        return $context;
    }
}
