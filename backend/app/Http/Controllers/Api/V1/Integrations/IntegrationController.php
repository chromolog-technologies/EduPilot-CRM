<?php

namespace App\Http\Controllers\Api\V1\Integrations;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;
        $integrations = Integration::where('organization_id', $organization->id)->get();

        return ApiResponse::success($integrations, 'Integrations retrieved.');
    }

    public function store(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'provider' => 'required|string|max:100',
        ]);

        $integration = Integration::create(array_merge($validated, [
            'organization_id' => $organization->id,
            'status' => 'connected',
            'created_by' => $request->user()->id,
        ]));

        return ApiResponse::success($integration, 'Integration connected.', 201);
    }
}
