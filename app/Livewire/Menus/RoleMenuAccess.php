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

    // Store previous state to detect changes
    public array $oldSelectedMenus = [];

    public function selectRole(int $roleId): void
    {
        $this->selectedRoleId = $roleId;

        // Load current menu access for this role
        $role = Role::with('menus')->find($roleId);
        $this->selectedMenus = $role->menus->pluck('id')->toArray();
        $this->oldSelectedMenus = $this->selectedMenus;
    }

    public function updatedSelectedMenus()
    {
        // Detect what was added or removed
        $added = array_diff($this->selectedMenus, $this->oldSelectedMenus);
        $removed = array_diff($this->oldSelectedMenus, $this->selectedMenus);

        if (! empty($added)) {
            foreach ($added as $menuId) {
                // If a child is checked, check the parent
                $menu = Menu::find($menuId);
                if ($menu && $menu->parent_id && ! in_array($menu->parent_id, $this->selectedMenus)) {
                    $this->selectedMenus[] = $menu->parent_id;
                }
            }
        }

        if (! empty($removed)) {
            foreach ($removed as $menuId) {
                // If a parent is unchecked, uncheck all children
                $childrenIds = Menu::where('parent_id', $menuId)->pluck('id')->toArray();
                if (! empty($childrenIds)) {
                    $this->selectedMenus = array_values(array_diff($this->selectedMenus, $childrenIds));
                }
            }
        }

        // Update old state
        $this->selectedMenus = array_values(array_unique($this->selectedMenus));
        $this->oldSelectedMenus = $this->selectedMenus;
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
