<?php

namespace App\Support;

/**
 * Generates and stores a per-request CSP nonce.
 *
 * The nonce is generated once and reused for the entire request lifecycle.
 * It should be included in all inline <script> tags and passed to the
 * Content-Security-Policy header via the SecurityHeaders middleware.
 */
class CspNonce
{
    protected string $nonce;

    public function __construct()
    {
        $this->nonce = base64_encode(random_bytes(16));
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
