<?php

namespace App\Http\Controllers\Api\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\LeadSource;
use App\Models\Setting;
use App\Services\User\UserService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index(Request $request)
    {
        return ApiResponse::success(
            Setting::query()->where('organization_id', $request->user()->organization_id)->get(),
            'Settings retrieved successfully.'
        );
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:100'],
            'value' => ['nullable', 'string'],
        ]);

        $setting = Setting::query()->updateOrCreate(
            [
                'organization_id' => $request->user()->organization_id,
                'key' => $data['key'],
            ],
            ['value' => $data['value'] ?? null]
        );

        return ApiResponse::success($setting, 'Setting saved successfully.');
    }

    public function users(Request $request)
    {
        $paginator = $this->userService->paginate($request->user(), $request->all());

        return ApiResponse::paginated($paginator, UserResource::collection($paginator->items())->resolve(), 'Users retrieved successfully.');
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string'],
            'role_id' => ['nullable', 'integer'],
            'branch_id' => ['nullable', 'integer'],
        ]);

        $user = $this->userService->create($request->user(), $data);

        return ApiResponse::created((new UserResource($user))->resolve(), 'User created successfully.');
    }

    public function leadSources()
    {
        return ApiResponse::success(LeadSource::query()->orderBy('name')->get(), 'Lead sources retrieved successfully.');
    }

    public function storeLeadSource(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:50'],
        ]);

        $source = LeadSource::create([
            'organization_id' => $request->user()->organization_id,
            'name' => $data['name'],
            'code' => $data['code'] ?? Str::slug($data['name']),
            'status' => 'active',
        ]);

        return ApiResponse::created($source, 'Lead source created successfully.');
    }
}
