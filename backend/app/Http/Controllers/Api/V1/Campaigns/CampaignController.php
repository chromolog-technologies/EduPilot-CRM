<?php

namespace App\Http\Controllers\Api\V1\Campaigns;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;
        $campaigns = Campaign::where('organization_id', $organization->id)->get();

        return ApiResponse::success($campaigns, 'Campaigns retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:100',
            'platform' => 'required|string|max:50',
            'campaign_type' => 'nullable|string|max:50',
            'budget' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $campaign = Campaign::create(array_merge($validated, [
            'organization_id' => $organization->id,
            'created_by' => $request->user()->id,
            'status' => 'active',
        ]));

        return ApiResponse::success($campaign, 'Campaign created successfully.', 201);
    }

    public function show(Campaign $campaign): JsonResponse
    {
        return ApiResponse::success($campaign, 'Campaign retrieved successfully.');
    }

    public function update(Request $request, Campaign $campaign): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:150',
            'platform' => 'sometimes|string|max:50',
            'status' => 'sometimes|string|max:30',
            'budget' => 'nullable|numeric',
        ]);

        $campaign->update($validated);

        return ApiResponse::success($campaign, 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign): JsonResponse
    {
        $campaign->delete();
        return ApiResponse::success(null, 'Campaign deleted successfully.');
    }
}
