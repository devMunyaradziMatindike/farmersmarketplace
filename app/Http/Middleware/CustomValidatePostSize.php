<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomValidatePostSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set custom post size limit (50MB)
        $maxPostSize = 50 * 1024 * 1024; // 50MB in bytes
        
        $contentLength = $request->server('CONTENT_LENGTH');
        
        if ($contentLength && $contentLength > $maxPostSize) {
            // Return a proper error response for Inertia.js
            if ($request->header('X-Inertia')) {
                return back()->withErrors([
                    'photos' => ['Total upload size is too large. Please reduce the number or size of images. Maximum size is 50MB.']
                ]);
            }
            
            return response()->json([
                'message' => 'Request entity too large. Maximum size is 50MB.',
                'errors' => [
                    'photos' => ['Total upload size is too large. Please reduce the number or size of images.']
                ]
            ], 413);
        }

        return $next($request);
    }
}



