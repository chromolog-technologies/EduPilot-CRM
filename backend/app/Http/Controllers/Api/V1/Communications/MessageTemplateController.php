<?php

namespace App\Http\Controllers\Api\V1\Communications;

use App\Http\Controllers\Controller;
use App\Models\MessageTemplate;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;
        $templates = MessageTemplate::where('organization_id', $organization->id)->get();

        return ApiResponse::success($templates, 'Message templates retrieved.');
    }

    public function store(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string',
            'channel' => 'required|string',
            'content' => 'required|string',
        ]);

        $template = MessageTemplate::create(array_merge($validated, [
            'organization_id' => $organization->id,
            'created_by' => $request->user()->id,
            'status' => 'approved',
        ]));

        return ApiResponse::success($template, 'Message template created.', 201);
    }
}
