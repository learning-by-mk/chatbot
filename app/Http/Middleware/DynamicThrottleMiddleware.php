<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class DynamicThrottleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'login.' . $request->ip() . '|' . $request->input('email');
        $maxAttempts = (int) Setting::getSettingValue('max_login_attempts', 5);
        $decayMinutes = (int) Setting::getSettingValue('lockout_time', 1);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => 'Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau ' . ceil($seconds / 60) . ' phút.',
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60)
            ], 429);
        }

        $response = $next($request);

        // Nếu đăng nhập thất bại (có thể kiểm tra status code hoặc session)
        if (
            $response->getStatusCode() === 401 ||
            ($request->routeIs('login') && session()->has('login_failed'))
        ) {
            RateLimiter::hit($key, 60 * $decayMinutes);
        }

        // Đăng nhập thành công thì clear
        if ($response->getStatusCode() === 200 && $request->routeIs('login')) {
            RateLimiter::clear($key);
        }

        return $response;
    }
}
