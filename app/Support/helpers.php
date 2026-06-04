<?php

use App\Support\CspNonce;

if (! function_exists('csp_nonce')) {
    /**
     * Get the CSP nonce for the current request.
     *
     * Usage in Blade: <script nonce="{{ csp_nonce() }}">
     */
    function csp_nonce(): string
    {
        return app(CspNonce::class)->get();
    }
}
