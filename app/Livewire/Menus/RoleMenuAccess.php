<?php

namespace App\Livewire\Menus;

use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Services\MenuService;
use Illuminate\Support\Collection;
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

    protected RoleRepositoryInterface $roleRepository;

    protected MenuRepositoryInterface $menuRepository;

    public function boot(
        RoleRepositoryInterface $roleRepository,
        MenuRepositoryInterface $menuRepository
    ): void {
        $this->roleRepository = $roleRepository;
        $this->menuRepository = $menuRepository;
    }

    public function selectRole(int $roleId): void
    {
        $this->selectedRoleId = $roleId;

        // Load current menu access for this role
        $role = $this->roleRepository->find($roleId);
        $role->load('menus');
        $this->selectedMenus = $role->menus->pluck('id')->toArray();
        $this->oldSelectedMenus = $this->selectedMenus;
    }

    /**
     * Keep the tree consistent after a click: a menu carries everything under it (checking checks
     * the children, unchecking unchecks them), and a checked child also needs its parent.
     */
    public function updatedSelectedMenus(): void
    {
        $selected = array_map('intval', $this->selectedMenus);
        $previous = array_map('intval', $this->oldSelectedMenus);

        // Only what the user clicked cascades downwards. A parent added because one child was
        // checked must not pull in that child's siblings.
        $added = array_diff($selected, $previous);
        $removed = array_diff($previous, $selected);

        $menus = $this->menuRepository->all();
        $parentOf = $menus->pluck('parent_id', 'id');
        $childrenOf = $menus->groupBy('parent_id');
        // Inactive menus are not shown in the tree, so checking a parent must not grant them.
        $visibleChildrenOf = $menus->where('is_active', true)->groupBy('parent_id');

        foreach ($added as $menuId) {
            $selected = array_merge($selected, $this->descendantsOf($menuId, $visibleChildrenOf));

            for ($parent = $parentOf->get($menuId); $parent; $parent = $parentOf->get($parent)) {
                $selected[] = (int) $parent;
            }
        }

        foreach ($removed as $menuId) {
            $selected = array_diff($selected, $this->descendantsOf($menuId, $childrenOf));
        }

        $this->selectedMenus = array_values(array_unique($selected));
        $this->oldSelectedMenus = $this->selectedMenus;
    }

    /** @return list<int> ids of every menu below this one */
    private function descendantsOf(int $menuId, Collection $childrenOf): array
    {
        $ids = [];

        foreach ($childrenOf->get($menuId, []) as $child) {
            $ids = [...$ids, $child->id, ...$this->descendantsOf($child->id, $childrenOf)];
        }

        return $ids;
    }

    public function saveMenuAccess(): void
    {
        if (! $this->selectedRoleId) {
            $this->dispatch('notify', text: 'Please select a role first.', variant: 'danger');

            return;
        }

        try {
            $role = DB::transaction(function () {
                $role = $this->roleRepository->findOrFail($this->selectedRoleId);
                $this->roleRepository->syncMenus($this->selectedRoleId, $this->selectedMenus);

                // Clear menu cache for this role
                $menuService = app(MenuService::class);
                $menuService->clearMenuCache($this->selectedRoleId);

                return $role;
            });

            $this->dispatch('notify', text: 'Menu access updated successfully for '.$role->name, variant: 'success');

            // Refresh the page to update sidebar (layout uses MenuService directly)
            $this->js('setTimeout(() => window.location.reload(), 1000)');
        } catch (\Exception $e) {
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
        $roles = $this->roleRepository->allWithUserCount();

        if ($this->search) {
            $roles = $roles->filter(function ($role) {
                return str_contains(strtolower($role->name), strtolower($this->search))
                    || str_contains(strtolower($role->slug), strtolower($this->search));
            });
        }

        $menus = $this->menuRepository->getMenuTree();

        $selectedRole = $this->selectedRoleId
            ? $this->roleRepository->find($this->selectedRoleId)
            : null;

        return view('livewire.menus.role-menu-access', [
            'roles' => $roles,
            'menus' => $menus,
            'selectedRole' => $selectedRole,
        ]);
    }
}
