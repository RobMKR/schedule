<?php

namespace App\Http\Middleware;

use App\Exceptions\V1\PermissionDeniedException;
use App\Services\Response\ResponseService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @param null $version
     * @return mixed
     * @throws PermissionDeniedException
     */
    public function handle($request, Closure $next, $version = null)
    {
        if (Auth::user()->isAdmin()) {
            return $next($request);
        }

        return ResponseService::errorMessage('Permission Denied', 403);

    }
}
