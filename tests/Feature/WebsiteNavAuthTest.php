<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteNavAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_is_offered_the_sign_in_link(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('href="'.route('login').'"', false);
        $response->assertSee('Portal Masuk');
        $response->assertDontSee('href="'.route('dashboard').'"', false);
    }

    public function test_a_signed_in_user_is_offered_the_dashboard_instead(): void
    {
        $user = new User(['name' => 'Contoh Pengguna', 'email' => 'contoh@example.test']);
        $user->id = 1;

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('href="'.route('dashboard').'"', false);
        $response->assertDontSee('href="'.route('login').'"', false);
        $response->assertDontSee('Portal Masuk');
    }
}
