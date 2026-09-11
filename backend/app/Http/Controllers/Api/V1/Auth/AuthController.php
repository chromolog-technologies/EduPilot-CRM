<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\UpdatePasswordRequest;
use App\Http\Requests\Api\V1\Auth\UpdateProfileRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\Auth\AuthService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
            $request->string('organization_code')->toString() ?: null
        );

        return ApiResponse::success([
            'token' => $result['token'],
            'token_type' => 'Bearer',
            'user' => (new UserResource($result['user']))->resolve(),
        ], 'Logged in successfully.');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return ApiResponse::success(null, 'Logged out successfully.');
    }

    public function refresh(Request $request)
    {
        $token = $this->authService->refresh($request->user());

        return ApiResponse::success([
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Token refreshed successfully.');
    }

    public function me(Request $request)
    {
        $user = $request->user()->load(['organization', 'role.permissions', 'branch']);

        return ApiResponse::success((new UserResource($user))->resolve(), 'Profile retrieved successfully.');
    }

    public function profile(UpdateProfileRequest $request)
    {
        $user = $this->authService->updateProfile($request->user(), $request->validated());

        return ApiResponse::success((new UserResource($user))->resolve(), 'Profile updated successfully.');
    }

    public function password(UpdatePasswordRequest $request)
    {
        $this->authService->updatePassword(
            $request->user(),
            $request->string('current_password')->toString(),
            $request->string('password')->toString()
        );

        return ApiResponse::success(null, 'Password updated successfully.');
    }
}
