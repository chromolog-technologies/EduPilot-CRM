<?php

namespace App\Http\Controllers\Api\V1\Automations;

use App\Http\Controllers\Controller;
use App\Models\FollowupSequence;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowupSequenceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;
        $sequences = FollowupSequence::where('organization_id', $organization->id)->with('steps')->get();

        return ApiResponse::success($sequences, 'Followup sequences retrieved.');
    }

    public function store(Request $request): JsonResponse
    {
        $organization = $request->user()->organization;

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'trigger_type' => 'required|string',
            'stop_on_reply' => 'nullable|boolean',
            'stop_on_conversion' => 'nullable|boolean',
            'steps' => 'nullable|array',
        ]);

        $sequence = FollowupSequence::create([
            'organization_id' => $organization->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'trigger_type' => $validated['trigger_type'],
            'stop_on_reply' => $validated['stop_on_reply'] ?? true,
            'stop_on_conversion' => $validated['stop_on_conversion'] ?? true,
            'created_by' => $request->user()->id,
            'status' => 'active',
        ]);

        if (!empty($validated['steps'])) {
            foreach ($validated['steps'] as $idx => $stepData) {
                $sequence->steps()->create([
                    'step_order' => $idx + 1,
                    'delay_value' => $stepData['delay_value'] ?? 0,
                    'delay_unit' => $stepData['delay_unit'] ?? 'hours',
                    'channel' => $stepData['channel'] ?? 'whatsapp',
                    'action_type' => $stepData['action_type'] ?? 'send_message',
                    'message_content' => $stepData['message_content'] ?? '',
                ]);
            }
        }

        return ApiResponse::success($sequence->load('steps'), 'Followup sequence created.', 201);
    }
}
