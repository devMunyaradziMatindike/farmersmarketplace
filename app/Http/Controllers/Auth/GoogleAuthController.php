<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google for authentication.
     */
    public function redirect(): RedirectResponse
    {
        // Store intended redirect in session before redirecting to Google
        // This ensures we can redirect the user to the right place after auth
        if (! session()->has('url.intended')) {
            session()->put('url.intended', route('dashboard'));
        }

        // Regenerate session ID to ensure session persistence across redirects
        // This helps maintain session state during the OAuth flow
        session()->regenerate();

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    /**
     * Handle Google callback.
     */
    public function callback(): RedirectResponse
    {
        try {
            // Get Google user data - ensure we're using stateful OAuth flow
            $googleUser = Socialite::driver('google')->user();

            // Find or create user
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Update existing user with latest Google information
                $user->update([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'auth_method' => 'google',
                ]);
            } else {
                // Create new user with Google information
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'auth_method' => 'google',
                    'role' => 'seller', // Default role for new Google users
                    'password' => bcrypt(Str::random(16)), // Random password for security
                ]);
            }

            // Regenerate session to prevent session fixation attacks
            // This is important after authentication
            session()->regenerate();

            // Log the user in with "remember me" enabled for persistent sessions
            Auth::login($user, true);

            // Get the intended URL from session (set in redirect method)
            $intendedUrl = session()->pull('url.intended', route('dashboard'));

            // Redirect to intended URL or dashboard
            return redirect()->intended($intendedUrl);
        } catch (\Exception $e) {
            Log::error('Google OAuth Error: '.$e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Google authentication failed. Please try again.');
        }
    }
}
