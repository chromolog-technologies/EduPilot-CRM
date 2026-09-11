<?php

namespace App\Http\Controllers\Api\V1\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Students\CreateStudentRequest;
use App\Http\Requests\Api\V1\Students\UpdateStudentRequest;
use App\Http\Resources\Api\V1\ActivityResource;
use App\Http\Resources\Api\V1\ConversationResource;
use App\Http\Resources\Api\V1\DocumentResource;
use App\Http\Resources\Api\V1\FollowupResource;
use App\Http\Resources\Api\V1\StudentResource;
use App\Models\Student;
use App\Services\Student\StudentProfileService;
use App\Services\Student\StudentService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(
        private StudentService $studentService,
        private StudentProfileService $studentProfileService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);
        $paginator = $this->studentService->paginate($request->user(), $request->all());

        return ApiResponse::paginated(
            $paginator,
            StudentResource::collection($paginator->items())->resolve(),
            'Students retrieved successfully.'
        );
    }

    public function store(CreateStudentRequest $request)
    {
        $this->authorize('create', Student::class);
        $student = $this->studentService->create($request->user(), $request->validated());

        return ApiResponse::created((new StudentResource($student))->resolve(), 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $this->authorize('view', $student);

        return ApiResponse::success(
            (new StudentResource($student->load(['parentContacts', 'pipelineStage', 'lead'])))->resolve(),
            'Student retrieved successfully.'
        );
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $this->authorize('update', $student);
        $student = $this->studentService->update($request->user(), $student, $request->validated());

        return ApiResponse::success((new StudentResource($student))->resolve(), 'Student updated successfully.');
    }

    public function destroy(Request $request, Student $student)
    {
        $this->authorize('delete', $student);
        $this->studentService->delete($request->user(), $student);

        return ApiResponse::success(null, 'Student deleted successfully.');
    }

    public function profile(Student $student)
    {
        $this->authorize('view', $student);

        return ApiResponse::success(
            (new StudentResource($this->studentProfileService->profile($student)))->resolve(),
            'Student profile retrieved successfully.'
        );
    }

    public function activities(Student $student)
    {
        $this->authorize('view', $student);
        $paginator = $student->activities()->with('user')->latest()->paginate(20);

        return ApiResponse::paginated($paginator, ActivityResource::collection($paginator->items())->resolve(), 'Activities retrieved successfully.');
    }

    public function followups(Student $student)
    {
        $this->authorize('view', $student);
        $paginator = $student->followups()->with('assignee')->latest()->paginate(20);

        return ApiResponse::paginated($paginator, FollowupResource::collection($paginator->items())->resolve(), 'Follow-ups retrieved successfully.');
    }

    public function documents(Student $student)
    {
        $this->authorize('view', $student);
        $paginator = $student->documents()->with('requirement')->latest()->paginate(20);

        return ApiResponse::paginated($paginator, DocumentResource::collection($paginator->items())->resolve(), 'Documents retrieved successfully.');
    }

    public function conversations(Student $student)
    {
        $this->authorize('view', $student);

        return ApiResponse::success(
            ConversationResource::collection($student->conversations()->with('messages')->latest()->get())->resolve(),
            'Conversations retrieved successfully.'
        );
    }
}
