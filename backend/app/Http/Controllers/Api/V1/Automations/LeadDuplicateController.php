<?php

namespace App\Http\Controllers\Api\V1\Automations;

use App\Http\Controllers\Controller;
use App\Models\LeadDuplicate;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadDuplicateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;
        $duplicates = LeadDuplicate::where('organization_id', $organization->id)
            ->with(['lead.student', 'duplicateLead.student'])
            ->get();

        return ApiResponse::success($duplicates, 'Duplicate records retrieved.');
    }

    public function confirm(Request $request, LeadDuplicate $duplicate): JsonResponse
    {
        $duplicate->update([
            'status' => 'confirmed',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return ApiResponse::success($duplicate, 'Duplicate confirmed.');
    }

    public function dismiss(Request $request, LeadDuplicate $duplicate): JsonResponse
    {
        $duplicate->update([
            'status' => 'not_duplicate',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return ApiResponse::success($duplicate, 'Duplicate dismissed.');
    }
}
