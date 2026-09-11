<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    public function handle(Request $request, Closure $next, string $limitKey): Response
    {
        $user = $request->user();

        if (!$user || !$user->organization) {
            return ApiResponse::error('Unauthorized organization context.', 401);
        }

        $organization = $user->organization;
        $plan = $organization->plan ?? 'gold';

        // Check seat limit when creating users
        if ($limitKey === 'seats') {
            $maxSeats = ($plan === 'basic') ? 10 : 50;
            $currentUsers = $organization->users()->count();

            if ($currentUsers >= $maxSeats) {
                return ApiResponse::error(
                    "Organization seat limit reached ({$currentUsers}/{$maxSeats} seats used). Upgrade plan to invite more staff.",
                    403
                );
            }
        }

        return $next($request);
    }
}
