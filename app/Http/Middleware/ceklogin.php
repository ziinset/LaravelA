<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ceklogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // The login controller stores the authenticated user id in session under
        // 'user_id' and sometimes 'admin_id'. Older code may check for 'id'.
        // Accept any of these as proof of authentication.
        $authenticated = session()->has('user_id') || session()->has('admin_id') || session()->has('id');
        if (! $authenticated) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
