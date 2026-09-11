<?php

namespace App\Http\Controllers\Api\V1\Leads;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Leads\AssignLeadRequest;
use App\Http\Requests\Api\V1\Leads\CreateLeadRequest;
use App\Http\Requests\Api\V1\Leads\UpdateLeadRequest;
use App\Http\Requests\Api\V1\Leads\UpdateLeadStatusRequest;
use App\Http\Resources\Api\V1\LeadResource;
use App\Models\Lead;
use App\Services\Lead\LeadAssignmentService;
use App\Services\Lead\LeadService;
use App\Services\Lead\LeadStatusService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        private LeadService $leadService,
        private LeadAssignmentService $leadAssignmentService,
        private LeadStatusService $leadStatusService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Lead::class);
        $paginator = $this->leadService->paginate($request->user(), $request->all());

        return ApiResponse::paginated(
            $paginator,
            LeadResource::collection($paginator->items())->resolve(),
            'Leads retrieved successfully.'
        );
    }

    public function store(CreateLeadRequest $request)
    {
        $this->authorize('create', Lead::class);
        $lead = $this->leadService->create($request->user(), $request->validated());

        return ApiResponse::created((new LeadResource($lead))->resolve(), 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);

        return ApiResponse::success(
            (new LeadResource($lead->load(['student', 'source', 'counselor'])))->resolve(),
            'Lead retrieved successfully.'
        );
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $this->authorize('update', $lead);
        $lead = $this->leadService->update($request->user(), $lead, $request->validated());

        return ApiResponse::success((new LeadResource($lead))->resolve(), 'Lead updated successfully.');
    }

    public function destroy(Request $request, Lead $lead)
    {
        $this->authorize('delete', $lead);
        $this->leadService->delete($request->user(), $lead);

        return ApiResponse::success(null, 'Lead deleted successfully.');
    }

    public function status(UpdateLeadStatusRequest $request, Lead $lead)
    {
        $this->authorize('update', $lead);
        $lead = $this->leadStatusService->updateStatus($request->user(), $lead, $request->string('lead_status')->toString());

        return ApiResponse::success((new LeadResource($lead))->resolve(), 'Lead status updated successfully.');
    }

    public function priority(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);
        $request->validate(['priority' => ['required', 'in:low,medium,high']]);
        $lead = $this->leadStatusService->updatePriority($request->user(), $lead, $request->string('priority')->toString());

        return ApiResponse::success((new LeadResource($lead))->resolve(), 'Lead priority updated successfully.');
    }

    public function assign(AssignLeadRequest $request, Lead $lead)
    {
        $this->authorize('assign', $lead);
        $lead = $this->leadAssignmentService->assign(
            $request->user(),
            $lead,
            (int) $request->validated('assigned_counselor_id')
        );

        return ApiResponse::success((new LeadResource($lead))->resolve(), 'Lead assigned successfully.');
    }
}
