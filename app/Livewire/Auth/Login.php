<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Login')]
class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        try {
            $this->validate();

            if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                $this->addError('email', 'These credentials do not match our records.');
                $this->dispatch('notify', text: 'Invalid credentials.', variant: 'danger');

                return;
            }

            $user = Auth::user();

            // Check if email is verified
            if (! $user->hasVerifiedEmail()) {
                Auth::logout();
                $this->addError('email', 'Please verify your email address before logging in. Check your inbox for the verification link.');
                $this->dispatch('notify', text: 'Email not verified. Please check your inbox.', variant: 'warning');

                return;
            }

            if (! $user->is_active) {
                Auth::logout();
                $this->addError('email', 'Your account has been deactivated.');
                $this->dispatch('notify', text: 'Account deactivated.', variant: 'danger');

                return;
            }

            // Set active role to default role on login
            $defaultRole = $user->getDefaultRole();
            if ($defaultRole) {
                $user->update(['role_id' => $defaultRole->id]);
            }

            session()->regenerate();
            $this->dispatch('notify', text: 'Welcome back!', variant: 'success');

            $this->redirect(route('dashboard'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
