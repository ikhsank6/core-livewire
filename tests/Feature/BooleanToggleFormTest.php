<?php

namespace Tests\Feature;

use App\Livewire\AboutUs\AboutUsIndex;
use App\Livewire\Carousels\CarouselIndex;
use App\Livewire\Menus\MenuIndex;
use App\Livewire\News\NewsIndex;
use App\Livewire\NewsCategories\NewsCategoryIndex;
use App\Livewire\Users\UserIndex;
use App\Models\AboutUs;
use App\Models\Carousel;
use App\Models\Menu;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * A yes/no toggle that is off has to stay "no" when its record is edited, not become empty.
 */
class BooleanToggleFormTest extends TestCase
{
    use RefreshDatabase;

    private Role $role;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $this->admin = User::factory()->create(['role_id' => $this->role->id]);
    }

    private function edit(string $component, $record)
    {
        return Livewire::actingAs($this->admin)->test($component)->call('edit', $record->uuid);
    }

    public function test_a_hidden_menu_can_be_saved_and_stays_hidden(): void
    {
        $menu = Menu::create(['name' => 'Tersembunyi', 'slug' => 'tersembunyi', 'order' => 1, 'is_active' => false]);

        $this->edit(MenuIndex::class, $menu)
            ->assertSet('data.is_active', 0)
            ->set('data.icon', 'folder-open')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertFalse($menu->fresh()->is_active);
        $this->assertSame('folder-open', $menu->fresh()->icon);
    }

    public function test_a_menu_can_be_switched_between_visible_and_hidden(): void
    {
        $menu = Menu::create(['name' => 'Tampil', 'slug' => 'tampil', 'order' => 1, 'is_active' => true]);

        $this->edit(MenuIndex::class, $menu)->assertSet('data.is_active', 1)->set('data.is_active', 0)->call('save');
        $this->assertFalse($menu->fresh()->is_active);

        $this->edit(MenuIndex::class, $menu)->set('data.is_active', 1)->call('save');
        $this->assertTrue($menu->fresh()->is_active);
    }

    public function test_a_suspended_user_can_be_edited_and_stays_suspended(): void
    {
        $user = User::factory()->create(['name' => 'Lama', 'is_active' => false, 'role_id' => $this->role->id]);
        $user->syncRoles([$this->role->id], $this->role->id);

        $this->edit(UserIndex::class, $user)
            ->assertSet('data.is_active', 0)
            ->set('data.name', 'Baru')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertSame('Baru', $user->fresh()->name);
        $this->assertFalse($user->fresh()->is_active);
    }

    public function test_switched_off_values_are_shown_as_off_in_every_form(): void
    {
        $category = NewsCategory::create(['name' => 'Kategori', 'slug' => 'kategori', 'is_active' => false]);
        $news = News::create([
            'news_category_id' => $category->id, 'title' => 'Judul', 'content' => '<p>Isi</p>',
            'is_active' => false, 'is_featured' => false,
        ]);
        $carousel = Carousel::create(['title' => 'Slide', 'image' => 'contoh.svg', 'order' => 1, 'is_active' => false]);
        $about = AboutUs::create(['company_name' => 'Lembaga', 'description' => 'Profil', 'is_active' => false]);

        $this->edit(NewsCategoryIndex::class, $category)->assertSet('data.is_active', 0);
        $this->edit(NewsIndex::class, $news)->assertSet('data.is_active', 0)->assertSet('data.is_featured', 0);
        $this->edit(CarouselIndex::class, $carousel)->assertSet('data.is_active', 0);
        $this->edit(AboutUsIndex::class, $about)->assertSet('data.is_active', 0);
    }
}
