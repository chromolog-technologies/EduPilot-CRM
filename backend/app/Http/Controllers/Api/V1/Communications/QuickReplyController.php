<?php

namespace App\Http\Controllers\Api\V1\Communications;

use App\Http\Controllers\Controller;
use App\Models\QuickReply;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuickReplyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;
        $quickReplies = QuickReply::where('organization_id', $organization->id)->get();

        return ApiResponse::success($quickReplies, 'Quick replies retrieved.');
    }

    public function store(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'shortcut' => 'required|string|max:50',
            'content' => 'required|string',
        ]);

        $quickReply = QuickReply::create(array_merge($validated, [
            'organization_id' => $organization->id,
            'created_by' => $request->user()->id,
            'status' => 'active',
        ]));

        return ApiResponse::success($quickReply, 'Quick reply created.', 201);
    }
}
