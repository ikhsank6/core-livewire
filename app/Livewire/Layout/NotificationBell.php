<?php

namespace App\Livewire\Layout;

use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationBell extends Component
{
    protected NotificationRepositoryInterface $notificationRepository;

    public function boot(NotificationRepositoryInterface $notificationRepository): void
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getNotificationsProperty()
    {
        return $this->notificationRepository->getForRole(
            Auth::user()->role_id,
            5,
            true
        );
    }

    public function getUnreadCountProperty()
    {
        return $this->notificationRepository->countUnreadForRole(Auth::user()->role_id);
    }

    public function markAsRead(string $uuid)
    {
        DB::beginTransaction();

        try {
            $notification = $this->notificationRepository->findByUuid($uuid);

            if ($notification && $notification->to_role_id == Auth::user()->role_id) {
                $this->notificationRepository->markAsRead($notification->id, Auth::user()->role_id);

                DB::commit();

                if ($notification->url) {
                    return redirect($notification->url);
                }
            } else {
                DB::commit();
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
