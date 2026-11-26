<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountMenuTest extends TestCase
{
    use RefreshDatabase;

    private Role $admin;

    private Role $member;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $this->member = Role::create(['name' => 'Anggota', 'slug' => 'anggota']);
        $this->user = User::factory()->create(['email' => 'akun@contoh.test', 'role_id' => $this->admin->id]);
    }

    private function menu(): string
    {
        $html = $this->actingAs($this->user->fresh())->get(route('dashboard'))->assertOk()->getContent();

        $start = strpos($html, 'id="account-menu"');
        $this->assertNotFalse($start, 'The account menu must be on the page');

        return substr($html, $start, strpos($html, '<!-- /account-menu -->', $start) - $start);
    }

    public function test_the_menu_names_the_account_and_offers_its_actions(): void
    {
        $this->user->syncRoles([$this->admin->id], $this->admin->id);
        $menu = $this->menu();

        foreach (['akun@contoh.test', 'Profil', 'Ganti kata sandi', 'Mode gelap', 'Keluar', 'Role: Admin'] as $text) {
            $this->assertStringContainsString($text, $menu);
        }

        $this->assertStringContainsString('role="switch"', $menu);
        $this->assertStringNotContainsString('Ganti role', $menu, 'Nothing to switch to with a single role');
    }

    public function test_only_the_other_roles_can_be_switched_to(): void
    {
        $this->user->syncRoles([$this->admin->id, $this->member->id], $this->admin->id);
        $menu = $this->menu();

        $this->assertStringContainsString('Ganti role', $menu);
        $this->assertStringContainsString('href="'.route('roles.switch', $this->member).'"', $menu);
        // The role in use is not a link: clicking it would only reload the page.
        $this->assertStringNotContainsString('href="'.route('roles.switch', $this->admin).'"', $menu);
        $this->assertMatchesRegularExpression('/aria-current="true"[\s\S]*?Admin[\s\S]*?Dipakai/', $menu);
    }
}
