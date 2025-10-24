<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct(protected OTPService $otpService) {}

    /**
     * Register a new user with phone number.
     */
    public function registerWithPhone(RegisterRequest $request): JsonResponse
    {
        // Create user account
        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'auth_method' => 'phone',
            'role' => 'seller',
        ]);

        // Generate and send OTP
        $otp = $this->otpService->generateAndStoreOTP($user);
        $sent = $this->otpService->sendOTP($user->phone_number, $otp);

        if (! $sent) {
            return response()->json([
                'message' => 'User registered but failed to send OTP. Please request a new OTP.',
                'user' => new UserResource($user),
            ], 201);
        }

        return response()->json([
            'message' => 'Registration successful. Please verify your phone number with the OTP sent via SMS.',
            'user' => new UserResource($user),
        ], 201);
    }
}
