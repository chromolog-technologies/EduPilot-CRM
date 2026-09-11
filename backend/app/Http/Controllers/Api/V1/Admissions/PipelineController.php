<?php

namespace App\Http\Controllers\Api\V1\Admissions;

use App\Http\Controllers\Controller;
use App\Models\PipelineStage;
use App\Models\Student;
use App\Services\Admissions\AdmissionsPipelineService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    public function __construct(private AdmissionsPipelineService $pipeline) {}

    public function stages(Request $request)
    {
        return ApiResponse::success($this->pipeline->stages($request->user()), 'Pipeline stages retrieved successfully.');
    }

    public function storeStage(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return ApiResponse::created($this->pipeline->createStage($request->user(), $data), 'Pipeline stage created successfully.');
    }

    public function updateStage(Request $request, PipelineStage $stage)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return ApiResponse::success($this->pipeline->updateStage($stage, $data), 'Pipeline stage updated successfully.');
    }

    public function destroyStage(PipelineStage $stage)
    {
        $this->pipeline->deleteStage($stage);

        return ApiResponse::success(null, 'Pipeline stage deleted successfully.');
    }

    public function stageHistory(Student $student)
    {
        $this->authorize('view', $student);

        return ApiResponse::success(
            $student->stageHistory()->with(['fromStage', 'toStage', 'changedByUser'])->latest()->get(),
            'Stage history retrieved successfully.'
        );
    }

    public function changeStage(Request $request, Student $student)
    {
        $this->authorize('update', $student);
        $data = $request->validate([
            'pipeline_stage_id' => ['required', 'integer'],
            'notes' => ['nullable', 'string'],
        ]);

        $student = $this->pipeline->changeStudentStage(
            $request->user(),
            $student,
            (int) $data['pipeline_stage_id'],
            $data['notes'] ?? null
        );

        return ApiResponse::success($student, 'Student stage updated successfully.');
    }
}
