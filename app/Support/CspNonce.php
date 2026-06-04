<?php

namespace App\Support;

/**
 * Generates and stores a per-request or per-session CSP nonce.
 *
 * The nonce is generated once and reused for SPA/Livewire navigations
 * to prevent Content Security Policy (CSP) blocking on dynamic updates.
 */
class CspNonce
{
    protected string $nonce;

    public function __construct()
    {
        try {
            if (app()->bound('session') && session()->isStarted()) {
                // If it is a Livewire AJAX request, reuse the active session nonce
                $isSpaOrAjax = request()->ajax() || 
                               request()->hasHeader('X-Livewire') || 
                               request()->hasHeader('X-Livewire-Navigate') || 
                               request()->hasHeader('X-Requested-With');

                if ($isSpaOrAjax && session()->has('csp_nonce')) {
                    $this->nonce = session()->get('csp_nonce');
                    return;
                }
            }
        } catch (\Throwable $e) {
            // Fallback
        }

        // Generate a new cryptographically secure random nonce
        $this->nonce = base64_encode(random_bytes(16));

        try {
            if (app()->bound('session') && session()->isStarted()) {
                session()->put('csp_nonce', $this->nonce);
            }
        } catch (\Throwable $e) {
            // Fallback
        }
    }

    /**
     * Get the CSP nonce for the current request.
     */
    public function get(): string
    {
        return $this->nonce;
    }

    public function __toString(): string
    {
        return $this->nonce;
    }
}
