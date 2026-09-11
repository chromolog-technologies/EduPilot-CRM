<?php

namespace App\Http\Controllers\Api\V1\Automations;

use App\Http\Controllers\Controller;
use App\Models\LeadCapture;
use App\Services\Lead\LeadCaptureService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadCaptureController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;
        $captures = LeadCapture::where('organization_id', $organization->id)->latest()->get();

        return ApiResponse::success($captures, 'Lead captures retrieved.');
    }

    public function store(Request $request, LeadCaptureService $service): JsonResponse
    {
        $organization = $request->user()->organization;
        $capture = $service->captureIncomingLead($organization, $request->all());

        return ApiResponse::success($capture, 'Incoming lead captured & processed.', 201);
    }
}
