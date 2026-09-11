<?php

namespace App\Http\Controllers\Api\V1\Automations;

use App\Http\Controllers\Controller;
use App\Models\LeadAssignmentRule;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadAssignmentRuleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;
        $rules = LeadAssignmentRule::where('organization_id', $organization->id)->orderBy('priority')->get();

        return ApiResponse::success($rules, 'Assignment rules retrieved.');
    }

    public function store(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'priority' => 'nullable|integer',
            'assignment_type' => 'required|string',
            'counselor_ids' => 'nullable|array',
            'max_active_leads' => 'nullable|integer',
        ]);

        $rule = LeadAssignmentRule::create(array_merge($validated, [
            'organization_id' => $organization->id,
            'is_active' => true,
            'created_by' => $request->user()->id,
        ]));

        return ApiResponse::success($rule, 'Lead assignment rule created.', 201);
    }
}
