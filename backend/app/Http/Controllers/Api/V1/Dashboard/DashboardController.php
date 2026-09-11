<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FollowupResource;
use App\Http\Resources\Api\V1\LeadResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\Dashboard\DashboardService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function summary(Request $request)
    {
        return ApiResponse::success($this->dashboardService->summary($request->user()), 'Dashboard summary retrieved successfully.');
    }

    public function leads(Request $request)
    {
        return ApiResponse::success(
            LeadResource::collection($this->dashboardService->leads($request->user()))->resolve(),
            'Dashboard leads retrieved successfully.'
        );
    }

    public function pipeline(Request $request)
    {
        return ApiResponse::success($this->dashboardService->pipeline($request->user()), 'Dashboard pipeline retrieved successfully.');
    }

    public function followups(Request $request)
    {
        return ApiResponse::success(
            FollowupResource::collection($this->dashboardService->followups($request->user()))->resolve(),
            'Dashboard follow-ups retrieved successfully.'
        );
    }

    public function counselors(Request $request)
    {
        return ApiResponse::success(
            UserResource::collection($this->dashboardService->counselors($request->user()))->resolve(),
            'Dashboard counselors retrieved successfully.'
        );
    }
}
