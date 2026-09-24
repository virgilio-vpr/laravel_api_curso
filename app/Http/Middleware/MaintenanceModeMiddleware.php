<?php

namespace App\Http\Middleware;

use App\Services\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the application is in maintenance mode
        if (config('app.maintenance_mode')) {
            // Return a 503 Service Unavailable response with a maintenance message
            return ApiResponse::error(
                'API is under maintenance. Please try again later.',
                503 // Service Unavailable
            );
        }
        return $next($request);
    }
}
