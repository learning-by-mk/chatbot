<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    public function me(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $user = $request->user()->load($with_vals);
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
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        $token = $user->createToken('Login at ' . now()->format('Y-m-d H:i:s'));

        return response([
            'user' => new UserResource($user),
            'token' => $token->plainTextToken,
            'isAdmin' => $user->hasRole('admin'),
        ]);
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
