<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KbmMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in by checking session
        // The system uses different session keys for different user types
        $authenticated = session()->has('user_id') || session()->has('admin_id') || session()->has('id');

        if (!$authenticated) {
            // Redirect to login page if not authenticated
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk mengakses jadwal KBM.');
        }

        return $next($request);
    }
}



