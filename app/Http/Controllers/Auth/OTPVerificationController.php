<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class OTPVerificationController extends Controller
{
    public function __construct(protected OTPService $otpService) {}

    /**
     * Display the OTP verification view.
     */
    public function create(Request $request): Response|RedirectResponse
    {
        $pendingUserId = session('pending_user_id');

        if (! $pendingUserId) {
            return redirect()->route('register-phone')->withErrors([
                'otp' => 'No pending registration found. Please register again.',
            ]);
        }

        $user = User::find($pendingUserId);

        if (! $user) {
            session()->forget('pending_user_id');

            return redirect()->route('register-phone')->withErrors([
                'otp' => 'Invalid registration session. Please register again.',
            ]);
        }

        return Inertia::render('Auth/VerifyOTP', [
            'phone_number' => $user->phone_number,
        ]);
    }

    /**
     * Verify OTP and complete registration.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $pendingUserId = session('pending_user_id');

        if (! $pendingUserId) {
            return redirect()->route('register-phone')->withErrors([
                'otp' => 'No pending registration found. Please register again.',
            ]);
        }

        $user = User::find($pendingUserId);

        if (! $user) {
            session()->forget('pending_user_id');

            return redirect()->route('register-phone')->withErrors([
                'otp' => 'Invalid registration session. Please register again.',
            ]);
        }

        // Verify OTP
        if (! $this->otpService->verifyOTP($user, $request->otp)) {
            return redirect()->back()->withErrors([
                'otp' => 'Invalid or expired OTP. Please try again.',
            ]);
        }

        // Clear pending session
        session()->forget('pending_user_id');

        // Log the user in
        Auth::login($user, true);

        return redirect()->route('home')->with('success', 'Registration completed successfully! Welcome to Musika Wedu!');
    }

    /**
     * Resend OTP.
     */
    public function resend(): RedirectResponse
    {
        $pendingUserId = session('pending_user_id');

        if (! $pendingUserId) {
            return redirect()->route('register-phone')->withErrors([
                'otp' => 'No pending registration found. Please register again.',
            ]);
        }

        $user = User::find($pendingUserId);

        if (! $user) {
            session()->forget('pending_user_id');

            return redirect()->route('register-phone')->withErrors([
                'otp' => 'Invalid registration session. Please register again.',
            ]);
        }

        // Generate and send new OTP
        $otp = $this->otpService->generateAndStoreOTP($user);
        $sent = $this->otpService->sendOTP($user->phone_number, $otp);

        if (! $sent) {
            return redirect()->back()->withErrors([
                'otp' => 'Failed to resend OTP. Please try again.',
            ]);
        }

        return redirect()->back()->with('success', 'New OTP sent to your phone number.');
    }
}
