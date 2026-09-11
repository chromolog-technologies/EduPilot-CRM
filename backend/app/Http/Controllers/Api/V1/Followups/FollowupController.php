<?php

namespace App\Http\Controllers\Api\V1\Followups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Followups\CreateFollowupRequest;
use App\Http\Requests\Api\V1\Followups\UpdateFollowupRequest;
use App\Http\Resources\Api\V1\FollowupResource;
use App\Models\Followup;
use App\Services\Followup\FollowupService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class FollowupController extends Controller
{
    public function __construct(private FollowupService $followupService) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Followup::class);
        $paginator = $this->followupService->paginate($request->user(), $request->all());

        return ApiResponse::paginated($paginator, FollowupResource::collection($paginator->items())->resolve(), 'Follow-ups retrieved successfully.');
    }

    public function store(CreateFollowupRequest $request)
    {
        $this->authorize('create', Followup::class);
        $followup = $this->followupService->create($request->user(), $request->validated());

        return ApiResponse::created((new FollowupResource($followup))->resolve(), 'Follow-up created successfully.');
    }

    public function show(Followup $followup)
    {
        $this->authorize('view', $followup);

        return ApiResponse::success((new FollowupResource($followup->load(['student', 'assignee'])))->resolve(), 'Follow-up retrieved successfully.');
    }

    public function update(UpdateFollowupRequest $request, Followup $followup)
    {
        $this->authorize('update', $followup);
        $followup = $this->followupService->update($followup, $request->validated());

        return ApiResponse::success((new FollowupResource($followup))->resolve(), 'Follow-up updated successfully.');
    }

    public function destroy(Followup $followup)
    {
        $this->authorize('delete', $followup);
        $this->followupService->delete($followup);

        return ApiResponse::success(null, 'Follow-up deleted successfully.');
    }

    public function complete(Request $request, Followup $followup)
    {
        $this->authorize('update', $followup);
        $followup = $this->followupService->complete($request->user(), $followup);

        return ApiResponse::success((new FollowupResource($followup))->resolve(), 'Follow-up completed successfully.');
    }

    public function cancel(Followup $followup)
    {
        $this->authorize('update', $followup);
        $followup = $this->followupService->cancel($followup);

        return ApiResponse::success((new FollowupResource($followup))->resolve(), 'Follow-up cancelled successfully.');
    }
}
