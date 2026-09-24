<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CorrelationIdMiddleware;
use App\Http\Middleware\MaintenanceModeMiddleware;
use App\Services\ApiResponse;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',


    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->api(prepend: [
            CorrelationIdMiddleware::class    
        ]);

        // Check if API is in maintenance mode.
        $middleware->api(prepend: [
            MaintenanceModeMiddleware::class
        ]);


        // Rate limiting middleware using the default api rate limiter.
        $middleware->api(prepend: [
            ThrottleRequests::class.':api' // use the 'api' rate limiter defined in RouteServiceProvider
        ]);
    
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // custom exception for Rate Limiting
        $exceptions->render(function (ThrottleRequestsException $e, $request){
        return ApiResponse::error('Too many requests', 429);
    });

    })->create();
