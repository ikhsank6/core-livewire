<?php

namespace App\Livewire\Menus;

use App\Models\Menu;
use App\Models\Role;
use App\Services\MenuService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Menu Access')]
class RoleMenuAccess extends Component
{
    #[Url]
    public $search = '';

    public ?int $selectedRoleId = null;

    public array $selectedMenus = [];

    public function selectRole(int $roleId): void
    {
        $this->selectedRoleId = $roleId;

        // Load current menu access for this role
        $role = Role::with('menus')->find($roleId);
        $this->selectedMenus = $role->menus->pluck('id')->toArray();
    }

    public function toggleMenu(int $menuId): void
    {
        if (in_array($menuId, $this->selectedMenus)) {
            $this->selectedMenus = array_values(array_diff($this->selectedMenus, [$menuId]));

            // Also uncheck children if parent is unchecked
            $children = Menu::where('parent_id', $menuId)->pluck('id')->toArray();
            $this->selectedMenus = array_values(array_diff($this->selectedMenus, $children));
        } else {
            $this->selectedMenus[] = $menuId;

            // Also check parent if child is checked
            $menu = Menu::find($menuId);
            if ($menu->parent_id && ! in_array($menu->parent_id, $this->selectedMenus)) {
                $this->selectedMenus[] = $menu->parent_id;
            }
        }
    }

    public function saveMenuAccess(): void
    {
        if (! $this->selectedRoleId) {
            $this->dispatch('notify', text: 'Please select a role first.', variant: 'danger');

            return;
        }

        DB::beginTransaction();

        try {
            $role = Role::findOrFail($this->selectedRoleId);
            $role->menus()->sync($this->selectedMenus);

            // Clear menu cache for this role
            $menuService = app(MenuService::class);
            $menuService->clearMenuCache($this->selectedRoleId);

            DB::commit();

            $this->dispatch('notify', text: 'Menu access updated successfully for '.$role->name, variant: 'success');

            // Refresh the page to update sidebar (layout uses MenuService directly)
            $this->js('setTimeout(() => window.location.reload(), 1000)');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function backToRoles(): void
    {
        $this->selectedRoleId = null;
        $this->selectedMenus = [];
    }

    public function render()
    {
        $roles = Role::withCount('users')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('slug', 'like', '%'.$this->search.'%')
            ->get();

        $menus = Menu::with('children')
            ->whereNull('parent_id')
            ->active()
            ->ordered()
            ->get();

        $selectedRole = $this->selectedRoleId
            ? Role::find($this->selectedRoleId)
            : null;

        return view('livewire.menus.role-menu-access', [
            'roles' => $roles,
            'menus' => $menus,
            'selectedRole' => $selectedRole,
        ]);
    }
}
