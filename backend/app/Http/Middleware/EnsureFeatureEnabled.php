<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        $user = $request->user();

        if (!$user || !$user->organization) {
            return ApiResponse::error('Unauthorized organization context.', 401);
        }

        $organization = $user->organization;

        if (!$organization->hasFeature($featureKey)) {
            return ApiResponse::error(
                "Feature '{$featureKey}' is not enabled for your organization plan ({$organization->plan}).",
                403
            );
        }

        return $next($request);
    }
}
