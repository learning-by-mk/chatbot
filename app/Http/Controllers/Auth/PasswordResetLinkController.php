<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            Log::info('Reset password request received', ['email' => $request->email]);

            $request->validate([
                'email' => ['required', 'email'],
            ]);

            // Kiểm tra xem email có tồn tại trong database hay không
            $user = \App\Models\User::where('email', $request->email)->first();
            if (!$user) {
                Log::warning('User email not found', ['email' => $request->email]);
                return response()->json([
                    'status' => false,
                    'message' => 'Chúng tôi không thể tìm thấy người dùng với địa chỉ email này.'
                ]);
            }

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $request->only('email')
            );

            Log::info('Reset password status', ['status' => $status]);

            return response()->json([
                'status' => $status == Password::RESET_LINK_SENT,
                'message' => __($status)
            ]);
        } catch (\Exception $e) {
            Log::error('Reset password error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra: " . ($e->getMessage()),
                'error_details' => $e->getMessage()
            ], 500);
        }
    }
}
