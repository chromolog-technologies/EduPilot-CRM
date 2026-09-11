<?php

namespace App\Http\Controllers\Api\V1\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tasks\CreateTaskRequest;
use App\Http\Requests\Api\V1\Tasks\UpdateTaskRequest;
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\Task;
use App\Services\Task\TaskService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Task::class);
        $paginator = $this->taskService->paginate($request->user(), $request->all());

        return ApiResponse::paginated($paginator, TaskResource::collection($paginator->items())->resolve(), 'Tasks retrieved successfully.');
    }

    public function store(CreateTaskRequest $request)
    {
        $this->authorize('create', Task::class);
        $task = $this->taskService->create($request->user(), $request->validated());

        return ApiResponse::created((new TaskResource($task))->resolve(), 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return ApiResponse::success((new TaskResource($task->load(['student', 'assignee'])))->resolve(), 'Task retrieved successfully.');
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task = $this->taskService->update($task, $request->validated());

        return ApiResponse::success((new TaskResource($task))->resolve(), 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $this->taskService->delete($task);

        return ApiResponse::success(null, 'Task deleted successfully.');
    }

    public function complete(Task $task)
    {
        $this->authorize('update', $task);
        $task = $this->taskService->complete($task);

        return ApiResponse::success((new TaskResource($task))->resolve(), 'Task completed successfully.');
    }
}
