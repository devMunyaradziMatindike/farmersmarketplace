<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Twilio\Rest\Client;

class OTPService
{
    protected Client $twilio;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    /**
     * Generate a 6-digit OTP.
     */
    public function generateOTP(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP via SMS using Twilio.
     */
    public function sendOTP(string $phoneNumber, string $otp): bool
    {
        try {
            $message = "Your Musika Wethu verification code is {$otp}. Sent from Musika Wethu. Valid for 5 minutes. A product of ©️Nesso Systems (Pvt) Ltd.";

            $this->twilio->messages->create(
                $phoneNumber,
                [
                    'from' => config('services.twilio.from'),
                    'body' => $message,
                ]
            );

            return true;
        } catch (\Exception $e) {
            \Log::error('Twilio OTP Send Error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Generate and store OTP for user.
     */
    public function generateAndStoreOTP(User $user): string
    {
        $otp = $this->generateOTP();
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        return $otp;
    }

    /**
     * Verify OTP for user.
     */
    public function verifyOTP(User $user, string $otp): bool
    {
        if ($user->otp !== $otp) {
            return false;
        }

        if ($user->otp_expires_at < Carbon::now()) {
            return false;
        }

        // Clear OTP after successful verification
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return true;
    }
}
