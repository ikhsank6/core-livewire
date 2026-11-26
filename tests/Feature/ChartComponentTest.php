<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ChartComponentTest extends TestCase
{
    use RefreshDatabase; // every view reads the site settings through a view composer

    public function test_the_screen_reader_table_is_hidden_by_a_wrapper(): void
    {
        foreach (['bar-chart', 'pie-chart'] as $component) {
            $html = Blade::render(
                "<x-ui.{$component} title=\"Contoh\" :items=\"\$items\" />",
                ['items' => [['label' => 'Satu', 'value' => 3]]]
            );

            // A table with sr-only keeps its full height and stretches the page below the footer.
            $this->assertDoesNotMatchRegularExpression('/<table[^>]*sr-only/', $html, $component);
            $this->assertMatchesRegularExpression('/<div class="sr-only">\s*<table>/', $html, $component);
            $this->assertStringContainsString('<th scope="row">Satu</th><td>3</td>', $html, $component);
        }
    }
}
