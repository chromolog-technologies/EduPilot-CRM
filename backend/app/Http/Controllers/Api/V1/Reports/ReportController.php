<?php

namespace App\Http\Controllers\Api\V1\Reports;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    public function leads(Request $request)
    {
        return ApiResponse::success($this->reportService->leads($request->user(), $request->all()), 'Lead report retrieved successfully.');
    }

    public function sources(Request $request)
    {
        return ApiResponse::success($this->reportService->sources($request->user(), $request->all()), 'Source report retrieved successfully.');
    }

    public function counselors(Request $request)
    {
        return ApiResponse::success($this->reportService->counselors($request->user(), $request->all()), 'Counselor report retrieved successfully.');
    }

    public function pipeline(Request $request)
    {
        return ApiResponse::success($this->reportService->pipeline($request->user(), $request->all()), 'Pipeline report retrieved successfully.');
    }

    public function followups(Request $request)
    {
        return ApiResponse::success($this->reportService->followups($request->user(), $request->all()), 'Follow-up report retrieved successfully.');
    }

    public function conversions(Request $request)
    {
        return ApiResponse::success($this->reportService->conversions($request->user(), $request->all()), 'Conversion report retrieved successfully.');
    }
}
