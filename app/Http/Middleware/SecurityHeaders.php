<?php

namespace App\Http\Middleware;

use App\Support\CspNonce;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Resolve the nonce and register it with Vite before the response is generated
        if (config('csp.enabled', true)) {
            $nonce = app(CspNonce::class)->get();
            \Illuminate\Support\Facades\Vite::useCspNonce($nonce);
        }

        $response = $next($request);

        // ─── Standard Security Headers ───────────────────────────────
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Cross-Origin isolation headers
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

        // Only set HSTS if connection is secure
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // ─── Content Security Policy ─────────────────────────────────
        if (config('csp.enabled', true)) {
            $this->applyCsp($request, $response);
            $this->injectNonces($response);
        }

        return $response;
    }

    /**
     * Build and apply the Content Security Policy header.
     */
    protected function applyCsp(Request $request, Response $response): void
    {
        $nonce = app(CspNonce::class)->get();

        $directives = [
            'default-src' => ["'self'"],

            'script-src' => array_merge(
                ["'self'", "'unsafe-eval'", "'nonce-{$nonce}'"],
                config('csp.extra.script-src', [])
            ),

            // 'unsafe-inline' is required for Filament, Flux, and Tailwind
            // dynamic styles. Using a nonce for styles is impractical with
            // these frameworks because they inject styles programmatically.
            'style-src' => array_merge(
                [
                    "'self'",
                    "'unsafe-inline'",
                    'https://fonts.googleapis.com',
                    'https://fonts.bunny.net',
                ],
                config('csp.extra.style-src', [])
            ),

            'font-src' => array_merge(
                [
                    "'self'",
                    'data:',
                    'https://fonts.googleapis.com',
                    'https://fonts.gstatic.com',
                    'https://fonts.bunny.net',
                ],
                config('csp.extra.font-src', [])
            ),

            'img-src' => array_merge(
                ["'self'", 'data:', 'blob:'],
                config('csp.extra.img-src', [])
            ),

            'connect-src' => array_merge(
                $this->buildConnectSrc($request),
                config('csp.extra.connect-src', [])
            ),

            'frame-src'  => ["'self'", 'https://www.google.com'],
            'worker-src' => ["'self'", 'blob:'],
            'child-src'  => ["'self'", 'blob:'],
            'object-src' => ["'none'"],
            'base-uri'   => ["'self'"],
            'form-action' => ["'self'"],
        ];

        $policy = collect($directives)
            ->map(fn (array $values, string $key) => $key.' '.implode(' ', $values))
            ->implode('; ');

        $headerName = config('csp.report_only', true)
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        $response->headers->set($headerName, $policy);
    }

    /**
     * Build the connect-src directive, allowing Vite HMR websocket in dev.
     */
    protected function buildConnectSrc(Request $request): array
    {
        $sources = ["'self'"];

        // Allow Vite HMR websocket in local development
        if (app()->environment('local')) {
            $sources[] = 'ws://localhost:*';
            $sources[] = 'wss://localhost:*';
            $sources[] = 'ws://127.0.0.1:*';
            $sources[] = 'http://localhost:*';
            $sources[] = 'http://127.0.0.1:*';
        }

        return $sources;
    }

    /**
     * Dynamically inject the CSP nonce into all script tags in the HTML response.
     */
    protected function injectNonces(Response $response): void
    {
        // Avoid modifying file downloads or streamed responses
        if ($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse ||
            $response instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
            return;
        }

        $contentType = $response->headers->get('Content-Type') ?? '';

        if (str_contains($contentType, 'text/html')) {
            $content = $response->getContent();
            if ($content === false || is_null($content)) {
                return;
            }

            $nonce = app(CspNonce::class)->get();

            // Match all <script ...> tags (case-insensitive)
            $content = preg_replace_callback(
                '/<script(\s[^>]*?)?>/i',
                function ($matches) use ($nonce) {
                    $attributes = $matches[1] ?? '';

                    // If it already has a nonce, do not add it again
                    if (preg_match('/nonce=/i', $attributes)) {
                        return $matches[0];
                    }

                    // Insert the nonce attribute
                    return "<script nonce=\"{$nonce}\"{$attributes}>";
                },
                $content
            );

            $response->setContent($content);
        }
    }
}
