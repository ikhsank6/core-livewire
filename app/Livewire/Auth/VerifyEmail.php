<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Verify Email')]
class VerifyEmail extends Component
{
    public bool $verified = false;

    public bool $alreadyVerified = false;

    public bool $invalidLink = false;

    /** The account was created with a generated password that was emailed to the user. */
    public bool $mustChangePassword = false;

    public function mount(Request $request, $id, $hash): void
    {
        // Only links this app signed, and only until they expire.
        if (! $request->hasValidRelativeSignature()) {
            $this->invalidLink = true;

            return;
        }

        $user = \App\Models\User::find($id);

        if (! $user) {
            $this->invalidLink = true;

            return;
        }

        // Verify hash matches
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            $this->invalidLink = true;

            return;
        }

        $this->mustChangePassword = (bool) $user->must_change_password;

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            $this->alreadyVerified = true;

            return;
        }

        // Mark email as verified and activate account
        if ($user->markEmailAsVerified()) {
            $user->update(['is_active' => true]); // Activate the account
            event(new Verified($user));
            $this->verified = true;
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
