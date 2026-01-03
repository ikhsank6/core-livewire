<?php

namespace App\Livewire\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Register')]
class Register extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public bool $registered = false;

    public function register(): void
    {
        DB::beginTransaction();

        try {
            $this->validate();

            // Get the default role (User)
            $defaultRole = Role::where('slug', 'user')->first();

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $defaultRole?->id,
                'is_active' => false, // Will be activated after email verification
            ]);

            // Attach role to user via pivot table
            if ($defaultRole) {
                $user->roles()->attach($defaultRole->id, ['is_default' => true]);
            }

            // Fire Registered event - this will trigger email verification
            event(new Registered($user));

            DB::commit();

            // Show success message
            $this->registered = true;
            $this->dispatch('notify', text: 'Registration successful! Please check your email to verify your account.', variant: 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
