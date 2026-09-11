<?php

namespace App\Http\Controllers\Api\V1\Activities;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ActivityResource;
use App\Services\Activity\ActivityService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function __construct(private ActivityService $activityService) {}

    public function index(Request $request)
    {
        $paginator = $this->activityService->paginate($request->user(), $request->all());

        return ApiResponse::paginated($paginator, ActivityResource::collection($paginator->items())->resolve(), 'Activities retrieved successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['nullable', 'integer'],
            'lead_id' => ['nullable', 'integer'],
            'type' => ['required', 'string'],
            'subject' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
        ]);

        $activity = $this->activityService->create($request->user(), $data);

        return ApiResponse::created((new ActivityResource($activity))->resolve(), 'Activity created successfully.');
    }
}
