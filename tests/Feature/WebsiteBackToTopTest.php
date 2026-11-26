<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteBackToTopTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_public_page_has_the_back_to_top_button(): void
    {
        foreach (['/', '/skema', '/jadwal', '/news', '/about'] as $url) {
            $response = $this->get($url);

            $response->assertOk();
            $response->assertSee('aria-label="Kembali ke atas"', false);
            $response->assertSee('<main id="main" tabindex="-1">', false);
        }
    }
}
