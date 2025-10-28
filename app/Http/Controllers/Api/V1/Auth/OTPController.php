<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\VerifyOTPRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OTPController extends Controller
{
    public function __construct(protected OTPService $otpService) {}

    /**
     * Verify OTP and issue authentication token.
     */
    public function verify(VerifyOTPRequest $request): JsonResponse
    {
        $user = User::where('phone_number', $request->phone_number)->firstOrFail();

        if (! $this->otpService->verifyOTP($user, $request->otp)) {
            return response()->json([
                'message' => 'Invalid or expired OTP.',
            ], 422);
        }

        // Issue Sanctum token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Phone number verified successfully.',
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }

    /**
     * Resend OTP to user's phone number.
     */
    public function resend(Request $request): JsonResponse
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
            'message' => 'OTP has been resent to your phone number.',
        ], 200);
    }
}
