<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Provides the catalog of Heroicons supported by both Flux UI and Blade Heroicons.
 */
class HeroiconCatalog
{
    /**
     * Return an alphabetically sorted map of icon name => inner SVG markup.
     *
     * @return array<string, string>
     */
    public static function all(): array
    {
        return Cache::rememberForever('heroicon_catalog_v1', function (): array {
            $fluxDir = base_path('vendor/livewire/flux/stubs/resources/views/flux/icon');
            $heroDir = base_path('vendor/blade-ui-kit/blade-heroicons/resources/svg');

            $fluxNames = array_map(
                fn (string $path): string => basename($path, '.blade.php'),
                glob($fluxDir.'/*.blade.php') ?: []
            );

            $icons = [];

            foreach (glob($heroDir.'/o-*.svg') ?: [] as $path) {
                $name = substr(basename($path, '.svg'), 2);

                if ($fluxNames !== [] && ! in_array($name, $fluxNames, true)) {
                    continue;
                }

                $raw = file_get_contents($path);

                if ($raw === false) {
                    continue;
                }

                $inner = trim((string) preg_replace('/^<svg[^>]*>\s*|\s*<\/svg>\s*$/i', '', trim($raw)));

                if ($inner !== '') {
                    $icons[$name] = $inner;
                }
            }

            ksort($icons);

            return $icons;
        });
    }
}
