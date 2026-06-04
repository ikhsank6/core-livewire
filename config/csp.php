<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Content Security Policy
    |--------------------------------------------------------------------------
    |
    | When disabled, the CSP header will not be sent. Useful for debugging
    | or if a third-party package conflicts with the policy.
    |
    */

    'enabled' => env('CSP_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Report-Only Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, violations are reported but not enforced. This lets you
    | monitor violations without breaking functionality. Recommended to
    | set to true on first deployment, then switch to false once stable.
    |
    */

    'report_only' => env('CSP_REPORT_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Additional Allowed Domains
    |--------------------------------------------------------------------------
    |
    | Extra domains to whitelist per directive (merged with the defaults
    | that the SecurityHeaders middleware already includes).
    |
    | Example:
    |   'script-src' => ['https://cdn.example.com'],
    |   'img-src'    => ['https://images.example.com'],
    |
    */

    'extra' => [
        'script-src' => [],
        'style-src'  => [],
        'font-src'   => [],
        'img-src'    => [],
        'connect-src' => [],
    ],

];
