<?php

namespace App\Http\Controllers\Api\V1\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\Notification\NotificationService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService) {}

    public function index(Request $request)
    {
        $paginator = $this->notificationService->paginate($request->user());

        return ApiResponse::paginated($paginator, $paginator->items(), 'Notifications retrieved successfully.');
    }

    public function markRead(Notification $notification)
    {
        if ((int) $notification->user_id !== (int) auth()->id()) {
            return ApiResponse::error('Forbidden.', 403);
        }

        return ApiResponse::success($this->notificationService->markRead($notification), 'Notification marked as read.');
    }
}
