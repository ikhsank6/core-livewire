<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordQueued extends ResetPasswordNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(#[\SensitiveParameter] $token)
    {
        parent::__construct($token);

        // Use the 'emails' queue as per user's running terminal
        $this->queue = 'emails';
    }
}
