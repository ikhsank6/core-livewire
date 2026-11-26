<?php

namespace Tests\Feature;

use App\Livewire\Menus\RoleMenuAccess;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MenuAccessCheckboxTest extends TestCase
{
    use RefreshDatabase;

    private Menu $parent;

    private Menu $news;

    private Menu $about;

    private Menu $hidden;

    private Menu $other;

    private Role $role;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parent = $this->menu('CMS');
        $this->news = $this->menu('News', $this->parent);
        $this->about = $this->menu('About Us', $this->parent);
        $this->hidden = $this->menu('Draft', $this->parent, active: false);
        $this->other = $this->menu('Dashboard');

        $this->role = Role::create(['name' => 'Editor', 'slug' => 'editor']);
        $this->admin = User::factory()->create(['role_id' => $this->role->id]);
    }

    private function menu(string $name, ?Menu $parent = null, bool $active = true): Menu
    {
        return Menu::create(['name' => $name, 'slug' => str($name)->slug(), 'parent_id' => $parent?->id, 'order' => 1, 'is_active' => $active]);
    }

    private function page()
    {
        return Livewire::actingAs($this->admin)->test(RoleMenuAccess::class)->call('selectRole', $this->role->id);
    }

    /** Checkbox values arrive from the browser as strings. */
    private function values(Menu ...$menus): array
    {
        return array_map(fn (Menu $menu) => (string) $menu->id, $menus);
    }

    private function assertSelected($page, Menu ...$menus): void
    {
        $this->assertEqualsCanonicalizing(
            array_map(fn (Menu $menu) => $menu->id, $menus),
            array_map('intval', $page->get('selectedMenus'))
        );
    }

    public function test_checking_a_parent_checks_the_children_shown_under_it(): void
    {
        $page = $this->page()->set('selectedMenus', $this->values($this->parent));

        // Not the hidden child: granting something that is not on screen would be a surprise.
        $this->assertSelected($page, $this->parent, $this->news, $this->about);
    }

    public function test_unchecking_a_parent_unchecks_all_of_its_children(): void
    {
        $this->role->menus()->sync([$this->parent->id, $this->news->id, $this->about->id, $this->hidden->id, $this->other->id]);

        $page = $this->page()->set('selectedMenus', $this->values($this->news, $this->about, $this->hidden, $this->other));

        $this->assertSelected($page, $this->other);
    }

    public function test_checking_one_child_checks_its_parent_but_not_its_siblings(): void
    {
        $page = $this->page()->set('selectedMenus', $this->values($this->news));

        $this->assertSelected($page, $this->news, $this->parent);
    }

    public function test_unchecking_one_child_leaves_the_others_alone(): void
    {
        $page = $this->page()->set('selectedMenus', $this->values($this->parent));
        $page->set('selectedMenus', $this->values($this->parent, $this->about));

        $this->assertSelected($page, $this->parent, $this->about);
    }

    public function test_the_cascaded_selection_is_what_gets_saved(): void
    {
        $this->page()->set('selectedMenus', $this->values($this->parent))->call('saveMenuAccess');

        $this->assertEqualsCanonicalizing(
            [$this->parent->id, $this->news->id, $this->about->id],
            $this->role->menus()->pluck('menus.id')->all()
        );
    }
}
