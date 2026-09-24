<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // rate limiting configuration
        RateLimiter::for('api', function ($request) {

            // Get the rate limit from the configuration, defaulting to 0 (no limit) if not set
            $rateLimit = config('app.rate_limit', 0);

            // If the rate limit is greater than 0, apply the limit; otherwise, disable rate limiting
            if ($rateLimit > 0) {
                return Limit::perMinute($rateLimit)->by($request->ip());
            } else {
                // If rate limit is set to 0 or less, disable rate limiting
                return Limit::none();
            }
        });
    }
}
