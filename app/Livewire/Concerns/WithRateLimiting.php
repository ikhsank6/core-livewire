<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Provides rate limiting helpers for sensitive Livewire actions
 * (e.g., login, change-password).
 *
 * The consuming component must define $rateLimitKey (string) or override
 * throttleKey() to return a unique key per user/IP.
 *
 * Usage:
 *   use App\Livewire\Concerns\WithRateLimiting;
 *
 *   protected string $rateLimitAction = 'change-password'; // optional prefix
 *   protected int    $rateLimitMax    = 5;                 // optional max attempts
 *
 *   public function someAction(): void
 *   {
 *       $this->ensureIsNotRateLimited();
 *       // ... do work ...
 *       $this->clearRateLimit();
 *   }
 */
trait WithRateLimiting
{
    /**
     * Maximum attempts before locking out.
     */
    protected int $rateLimitMax = 5;

    /**
     * Lock out duration in seconds.
     */
    protected int $rateLimitDecay = 300; // 5 minutes

    /**
     * Override in the consuming component to set the action label.
     * Example: protected function rateLimitAction(): string { return 'login'; }
     */
    protected function rateLimitAction(): string
    {
        return 'action';
    }

    /**
     * Build a unique throttle key for the current user + IP.
     */
    protected function throttleKey(): string
    {
        return \Str::transliterate(
            $this->rateLimitAction().'|'.(\Auth::id() ?? 'guest').'|'.request()->ip()
        );
    }

    /**
     * Throw a ValidationException when the rate limit is exceeded.
     *
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(string $errorField = 'email'): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $this->rateLimitMax)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            $errorField => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Record a failed attempt against the rate limiter.
     */
    protected function hitRateLimit(): void
    {
        RateLimiter::hit($this->throttleKey(), $this->rateLimitDecay);
    }

    /**
     * Clear the rate limiter on success.
     */
    protected function clearRateLimit(): void
    {
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Check whether the current throttle key is locked out.
     */
    protected function isRateLimited(): bool
    {
        return RateLimiter::tooManyAttempts($this->throttleKey(), $this->rateLimitMax);
    }
}
