<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CorrelationIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if de correlation ID exists, otherwise create one
        $correlationId = $request->header('X-Correlation-ID') ?: Str::uuid()->toString();

        // Set the header for the request
        $request->headers->set('X-Correlation-ID', $correlationId);

        // Proceed with the request and get the response
        $response = $next($request);

        // Set the correlation ID header for the response
        $response->headers->set('X-Correlation-ID', $correlationId);

        // Return the response
        return $response;
    }
}
