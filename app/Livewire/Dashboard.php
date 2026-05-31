<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $totalUsers     = User::count();
        $activeUsers    = User::where('is_active', true)->whereNotNull('email_verified_at')->count();
        $pendingUsers   = User::where('is_active', true)->whereNull('email_verified_at')->count();
        $suspendedUsers = User::where('is_active', false)->count();
        $totalRoles     = Role::count();

        return view('livewire.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'pendingUsers',
            'suspendedUsers',
            'totalRoles',
        ));
    }
}
