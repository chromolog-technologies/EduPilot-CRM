<?php

namespace App\Services\Notification;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function paginate(User $user)
    {
        return Notification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);
    }

    public function markRead(Notification $notification): Notification
    {
        $notification->update(['read_at' => now()]);

        return $notification;
    }
}
