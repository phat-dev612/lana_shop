<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCloudinaryConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if Cloudinary is configured
        $cloudName = config('cloudinary.cloud_name');
        $apiKey = config('cloudinary.api_key');
        $apiSecret = config('cloudinary.api_secret');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Cloudinary configuration is missing. Please check your .env file.',
                ], 500);
            }

            return redirect()->back()->with('error', 'Cloudinary configuration is missing. Please check your .env file.');
        }

        return $next($request);
    }
} 