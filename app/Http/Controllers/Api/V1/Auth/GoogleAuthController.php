<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    /**
     * Handle Google OAuth callback.
     * Expects google_id, email, and name from the frontend.
     */
    public function handleCallback(Request $request): JsonResponse
    {
        $request->validate([
            'google_id' => ['required', 'string'],
            'email' => ['required', 'email'],
            'name' => ['required', 'string'],
        ]);

        // Find or create user
        $user = User::where('google_id', $request->google_id)
            ->orWhere('email', $request->email)
            ->first();

        if ($user) {
            // Update existing user's Google ID if not set
            if (! $user->google_id) {
                $user->update([
                    'google_id' => $request->google_id,
                    'auth_method' => 'google',
                ]);
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'google_id' => $request->google_id,
                'auth_method' => 'google',
                'role' => 'seller',
            ]);
        }

        // Issue Sanctum token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Google authentication successful.',
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }
}
