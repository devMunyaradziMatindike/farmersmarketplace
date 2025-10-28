<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function __construct(protected OTPService $otpService) {}

    /**
     * Send OTP for password reset.
     */
    public function sendResetLink(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'exists:users,phone_number'],
        ]);

        $user = User::where('phone_number', $request->phone_number)->firstOrFail();

        $otp = $this->otpService->generateAndStoreOTP($user);
        $sent = $this->otpService->sendOTP($user->phone_number, $otp);

        if (! $sent) {
            return response()->json([
                'message' => 'Failed to send OTP. Please try again later.',
            ], 500);
        }

        return response()->json([
            'message' => 'Password reset OTP has been sent to your phone number.',
        ], 200);
    }

    /**
     * Reset password using OTP.
     */
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'exists:users,phone_number'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('phone_number', $request->phone_number)->firstOrFail();

        if (! $this->otpService->verifyOTP($user, $request->otp)) {
            return response()->json([
                'message' => 'Invalid or expired OTP.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Password has been reset successfully.',
        ], 200);
    }
}
