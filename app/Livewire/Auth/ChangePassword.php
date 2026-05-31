<?php

namespace App\Livewire\Auth;

use App\Livewire\Concerns\WithNotifications;
use App\Livewire\Concerns\WithRateLimiting;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Change Password')]
class ChangePassword extends Component
{
    use \App\Livewire\Concerns\WithPasswordValidation;
    use WithNotifications;
    use WithRateLimiting;

    protected function rateLimitAction(): string
    {
        return 'change-password';
    }


    #[Rule('required|current_password')]
    public string $current_password = '';

    public string $password = '';

    public function rules()
    {
        return [
            'current_password' => 'required|current_password',
            'password' => $this->getPasswordRules(),
        ];
    }

    public string $password_confirmation = '';

    public function changePassword(UserRepositoryInterface $userRepository): void
    {
        $this->validate();

        try {
            $this->ensureIsNotRateLimited(errorField: 'current_password');

            $userRepository->updatePassword(Auth::id(), $this->password);

            $this->clearRateLimit();

            $this->reset(['current_password', 'password', 'password_confirmation']);

            $this->notifySuccess('Password changed successfully.');
        } catch (ValidationException $e) {
            $this->hitRateLimit();

            if ($this->isRateLimited()) {
                $this->notifyError('Too many attempts. Please try again later.');
            }

            throw $e;
        } catch (\Exception $e) {
            $this->notifyError('Error: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.change-password');
    }
}
