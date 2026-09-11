<?php

namespace App\Http\Controllers\Api\V1\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\WebhookEvent;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function whatsapp(Request $request): JsonResponse
    {
        $organization = $request->user()?->organization;

        $event = WebhookEvent::create([
            'organization_id' => $organization?->id ?? 1,
            'event_type' => 'whatsapp.message',
            'payload' => $request->all(),
            'processing_status' => 'processed',
            'processed_at' => now(),
        ]);

        return ApiResponse::success($event, 'WhatsApp Webhook event processed successfully.');
    }
}
