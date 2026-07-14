<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;

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
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('forgot-password', function (Request $request) {
            $key = $request->ip() . '|' . ($request->input('email', ''));
            return Limit::perMinute(3)->by($key)->response(function () {
                return response()->json([
                    'code' => 429,
                    'error' => 'forgot.error_throttle',
                ], 429);
            });
        });

        RateLimiter::for('reset-password', function (Request $request) {
            $key = $request->ip() . '|' . ($request->input('email', ''));
            return Limit::perMinute(5)->by($key)->response(function () {
                return response()->json([
                    'code' => 429,
                    'error' => 'reset.error_throttle',
                ], 429);
            });
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
            // Cambiamos el dominio del backend por el del frontend
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:8051');
            
            // Esto enviará al usuario a: http://tu-frontend/verify-email?verify_url=...
            return $frontendUrl . '/verify-email?verify_url=' . urlencode($verifyUrl);
        });
    }
}
