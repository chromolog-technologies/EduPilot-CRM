<?php

namespace App\Http\Controllers\Api\V1\AI;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AiMessage;
use App\Services\AI\MessageGenerationService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiMessageController extends Controller
{
    public function generate(Request $request, MessageGenerationService $service): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'purpose' => 'required|string',
            'language' => 'nullable|string',
            'tone' => 'nullable|string',
        ]);

        $student = Student::findOrFail($validated['student_id']);
        $aiMessage = $service->generateDraft(
            $student,
            $validated['purpose'],
            $validated['language'] ?? 'English',
            $validated['tone'] ?? 'professional',
            $request->user()
        );

        return ApiResponse::success($aiMessage, 'AI draft generated successfully.', 201);
    }

    public function approve(Request $request, AiMessage $message): JsonResponse
    {
        $message->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return ApiResponse::success($message, 'AI draft approved.');
    }
}
