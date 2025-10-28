<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class PhoneRegistrationController extends Controller
{
    public function __construct(protected OTPService $otpService) {}

    /**
     * Display the phone registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/RegisterPhone');
    }

    /**
     * Handle phone registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'phone_number' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/|unique:users,phone_number',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create user account (not verified yet)
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'auth_method' => 'phone',
            'role' => 'seller',
        ]);

        // Generate and send OTP
        $otp = $this->otpService->generateAndStoreOTP($user);
        $sent = $this->otpService->sendOTP($user->phone_number, $otp);

        if (!$sent) {
            return redirect()->back()->withErrors([
                'phone_number' => 'Failed to send OTP. Please try again.',
            ]);
        }

        // Store user ID in session for OTP verification
        session(['pending_user_id' => $user->id]);

        return redirect()->route('verify-otp')->with('success', 'OTP sent to your phone number. Please verify to complete registration.');
    }
}