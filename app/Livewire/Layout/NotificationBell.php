<?php

namespace App\Livewire\Layout;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationBell extends Component
{
    public function getNotificationsProperty()
    {
        return Notification::where('to_role_id', Auth::user()->role_id)
            ->latest()
            ->take(5)
            ->get();
    }

    public function getUnreadCountProperty()
    {
        return Notification::where('to_role_id', Auth::user()->role_id)
            ->where('read', false)
            ->count();
    }

    public function markAsRead($id)
    {
        DB::beginTransaction();

        try {
            $notification = Notification::find($id);
            if ($notification && $notification->to_role_id == Auth::user()->role_id) {
                $notification->update(['read' => true]);
            }

            DB::commit();

            if ($notification?->url) {
                return redirect($notification->url);
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function render()
    {
        return view('livewire.layout.notification-bell');
    }
}
