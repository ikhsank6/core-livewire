<?php

namespace App\Livewire\Layout;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Notifications')]
class NotificationIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $filter = 'all'; // all, unread, read

    public function markAsRead(int $id): void
    {
        DB::beginTransaction();

        try {
            $notification = Notification::find($id);
            if ($notification && $notification->to_role_id == Auth::user()->role_id) {
                $notification->update(['read' => true]);
            }

            DB::commit();

            $this->dispatch('notify', text: 'Notification marked as read.', variant: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function markAllAsRead(): void
    {
        DB::beginTransaction();

        try {
            Notification::where('to_role_id', Auth::user()->role_id)
                ->where('read', false)
                ->update(['read' => true]);

            DB::commit();

            $this->dispatch('notify', text: 'All notifications marked as read.', variant: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function delete(int $id): void
    {
        DB::beginTransaction();

        try {
            $notification = Notification::find($id);
            if ($notification && $notification->to_role_id == Auth::user()->role_id) {
                $notification->delete();
            }

            DB::commit();

            $this->dispatch('notify', text: 'Notification deleted.', variant: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function deleteAllRead(): void
    {
        DB::beginTransaction();

        try {
            Notification::where('to_role_id', Auth::user()->role_id)
                ->where('read', true)
                ->delete();

            DB::commit();

            $this->dispatch('notify', text: 'All read notifications deleted.', variant: 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', text: 'Error: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function getUnreadCountProperty(): int
    {
        return Notification::where('to_role_id', Auth::user()->role_id)
            ->where('read', false)
            ->count();
    }

    public function render()
    {
        $query = Notification::with('fromRole')
            ->where('to_role_id', Auth::user()->role_id);

        if ($this->filter === 'unread') {
            $query->where('read', false);
        } elseif ($this->filter === 'read') {
            $query->where('read', true);
        }

        return view('livewire.layout.notification-index', [
            'notifications' => $query->latest()->paginate(15),
        ]);
    }
}
