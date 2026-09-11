<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganization
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->organization_id) {
            return ApiResponse::error('Organization could not be resolved.', 403);
        }

        $request->attributes->set('organization_id', $user->organization_id);

        return $next($request);
    }
}
