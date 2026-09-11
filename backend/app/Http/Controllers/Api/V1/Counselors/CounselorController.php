<?php

namespace App\Http\Controllers\Api\V1\Counselors;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FollowupResource;
use App\Http\Resources\Api\V1\StudentResource;
use App\Http\Resources\Api\V1\TaskResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\Student;
use App\Models\User;
use App\Services\Counselor\CounselorAssignmentService;
use App\Services\User\UserService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class CounselorController extends Controller
{
    public function __construct(
        private UserService $userService,
        private CounselorAssignmentService $counselorAssignmentService
    ) {}

    public function index(Request $request)
    {
        return ApiResponse::success(
            UserResource::collection($this->userService->counselors($request->user()))->resolve(),
            'Counselors retrieved successfully.'
        );
    }

    public function show(User $counselor)
    {
        return ApiResponse::success((new UserResource($counselor->load('role')))->resolve(), 'Counselor retrieved successfully.');
    }

    public function students(User $counselor)
    {
        $students = Student::query()
            ->whereHas('counselorAssignments', fn ($q) => $q->where('counselor_id', $counselor->id)->where('is_active', true))
            ->paginate(20);

        return ApiResponse::paginated($students, StudentResource::collection($students->items())->resolve(), 'Counselor students retrieved successfully.');
    }

    public function tasks(User $counselor)
    {
        $tasks = $counselor->id
            ? \App\Models\Task::query()->where('assigned_to', $counselor->id)->latest()->paginate(20)
            : collect();

        return ApiResponse::paginated($tasks, TaskResource::collection($tasks->items())->resolve(), 'Counselor tasks retrieved successfully.');
    }

    public function followups(User $counselor)
    {
        $followups = \App\Models\Followup::query()->where('assigned_to', $counselor->id)->latest()->paginate(20);

        return ApiResponse::paginated($followups, FollowupResource::collection($followups->items())->resolve(), 'Counselor follow-ups retrieved successfully.');
    }

    public function assign(Request $request, Student $student)
    {
        $this->authorize('update', $student);
        $data = $request->validate(['counselor_id' => ['required', 'integer']]);
        $assignment = $this->counselorAssignmentService->assign($student, (int) $data['counselor_id'], $request->user());

        return ApiResponse::success($assignment, 'Counselor assigned successfully.');
    }

    public function reassign(Request $request, Student $student)
    {
        $this->authorize('update', $student);
        $data = $request->validate(['counselor_id' => ['required', 'integer']]);
        $assignment = $this->counselorAssignmentService->reassign($student, (int) $data['counselor_id'], $request->user());

        return ApiResponse::success($assignment, 'Counselor reassigned successfully.');
    }
}
