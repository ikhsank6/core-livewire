<?php

namespace App\Livewire\Concerns;

/**
 * Provides helper methods for dispatching consistent toast notifications
 * and wrapping operations in a try/catch with automatic error toasting.
 *
 * Usage:
 *   use App\Livewire\Concerns\WithNotifications;
 *
 *   $this->notifySuccess('Record saved.');
 *   $this->notifyError('Something went wrong.');
 *   $this->attempt(fn () => $this->repo->save($data), 'Record saved.');
 */
trait WithNotifications
{
    /**
     * Dispatch a success toast notification.
     */
    public function notifySuccess(string $message): void
    {
        $this->dispatch('notify', text: $message, variant: 'success');
    }

    /**
     * Dispatch a danger/error toast notification.
     */
    public function notifyError(string $message): void
    {
        $this->dispatch('notify', text: $message, variant: 'danger');
    }

    /**
     * Dispatch a warning toast notification.
     */
    public function notifyWarning(string $message): void
    {
        $this->dispatch('notify', text: $message, variant: 'warning');
    }

    /**
     * Execute a callable, dispatching a success notification on success
     * and an error notification (with the exception message) on failure.
     *
     * Returns true on success, false on failure.
     *
     * Example:
     *   $this->attempt(function () use ($data) {
     *       $this->repo->update($data);
     *   }, 'Record updated successfully.');
     */
    public function attempt(callable $callback, string $successMessage): bool
    {
        try {
            $callback();
            $this->notifySuccess($successMessage);

            return true;
        } catch (\Exception $e) {
            $this->notifyError('Error: '.$e->getMessage());

            return false;
        }
    }
}
