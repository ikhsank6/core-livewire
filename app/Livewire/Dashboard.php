<?php

namespace App\Livewire;

use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    protected UserRepositoryInterface $userRepository;

    protected RoleRepositoryInterface $roleRepository;

    public function boot(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ): void {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function render()
    {
        $stats = $this->userRepository->getDashboardStatistics();

        $usersPerRole = $this->roleRepository->allWithUserCount()
            ->sortByDesc('users_count')
            ->map(fn ($role) => ['label' => $role->name, 'value' => (int) $role->users_count])
            ->values();

        return view('livewire.dashboard', [
            'totalUsers'     => $stats['total'],
            'activeUsers'    => $stats['active'],
            'pendingUsers'   => $stats['pending'],
            'suspendedUsers' => $stats['suspended'],
            'totalRoles'     => $this->roleRepository->count(),
            'usersPerRole'   => $usersPerRole,
        ]);
    }
}
