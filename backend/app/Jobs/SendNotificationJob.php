<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $organizationId,
        public int $userId,
        public string $type,
        public string $title,
        public string $message
    ) {}

    public function handle(): void
    {
        Notification::withoutGlobalScopes()->create([
            'organization_id' => $this->organizationId,
            'user_id' => $this->userId,
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
        ]);
    }
}
