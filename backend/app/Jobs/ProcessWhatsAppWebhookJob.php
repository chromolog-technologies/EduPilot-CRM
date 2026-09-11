<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessWhatsAppWebhookJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $payload) {}

    public function handle(): void
    {
        // Gold: map provider webhooks onto conversations/messages.
    }
}
