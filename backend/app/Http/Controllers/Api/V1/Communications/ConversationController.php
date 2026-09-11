<?php

namespace App\Http\Controllers\Api\V1\Communications;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ConversationResource;
use App\Http\Resources\Api\V1\MessageResource;
use App\Models\Conversation;
use App\Services\Communication\CommunicationService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(private CommunicationService $communicationService) {}

    public function messages(Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $paginator = $this->communicationService->messages($conversation);

        return ApiResponse::paginated($paginator, MessageResource::collection($paginator->items())->resolve(), 'Messages retrieved successfully.');
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $data = $request->validate(['body' => ['required', 'string']]);
        $message = $this->communicationService->sendMessage($request->user(), $conversation, $data['body']);

        return ApiResponse::created((new MessageResource($message))->resolve(), 'Message sent successfully.');
    }
}
