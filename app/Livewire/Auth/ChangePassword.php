<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Change Password')]
class ChangePassword extends Component
{
    #[Rule('required|current_password')]
    public string $current_password = '';

    #[Rule('required|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function changePassword(): void
    {
        DB::beginTransaction();
        $this->validate();
        try {
            $user = Auth::user();

            $user->update([
                'password' => Hash::make($this->password),
            ]);

            DB::commit();

            $this->reset(['current_password', 'password', 'password_confirmation']);

            $this->dispatch('notify', text: 'Password changed successfully.', variant: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.auth.change-password');
    }
}
