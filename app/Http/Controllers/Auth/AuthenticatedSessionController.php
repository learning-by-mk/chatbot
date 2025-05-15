<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    public function me(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $user = $request->user();
        $user->load($with_vals);
        $userResource = new UserResource($user);
        return response()->json([
            ...$userResource->toArray($request)
        ], 200);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $maxAttempts = (int) Setting::getSettingValue('max_login_attempts', 5);
        $attempts = RateLimiter::attempts($request->throttleKey());
        $remainingAttempts = $maxAttempts - $attempts;

        // Nếu số lần còn lại ít, thêm cảnh báo vào response
        $warning = null;
        if ($remainingAttempts <= 2 && $remainingAttempts > 0) {
            $warning = "Còn {$remainingAttempts} lần thử đăng nhập. Sau đó tài khoản sẽ bị khóa tạm thời.";
        }
        if ($remainingAttempts <= 0) {
            $seconds = RateLimiter::availableIn($request->throttleKey());
            $locked_time = (int) Setting::getSettingValue('lockout_time', 60);
            $warning = "Tài khoản đã bị khóa tạm thời. Vui lòng thử lại sau " . ceil($seconds / 60) . " phút.";
        }
        try {
            $request->authenticate();

            $request->session()->regenerate();

            $user = $request->user();
            $token = $user->createToken('Login at ' . now()->format('Y-m-d H:i:s'));

            return response([
                'user' => new UserResource($user),
                'token' => $token->plainTextToken,
                'isAdmin' => $user->hasRole('admin'),
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'warning' => $warning,
                'locked_time' => $locked_time ?? 0,
            ], 422);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }
}
