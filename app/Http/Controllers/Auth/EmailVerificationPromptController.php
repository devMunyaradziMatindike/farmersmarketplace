<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $status = session('status');
        $isSeller = $request->user()->isSeller();
        
        return Inertia::render('Auth/VerifyEmail', [
            'status' => $status,
            'isSeller' => $isSeller,
            'userRole' => $request->user()->role,
        ]);
    }
}
