<?php

namespace App\Http\Middleware;

use App\Models\admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CrudDataSiswaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in first
        $authenticated = session()->has('user_id') || session()->has('admin_id') || session()->has('id');

        if (!$authenticated) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Check if user is admin
        $isAdmin = false;

        // Check if admin_id exists in session
        if (session()->has('admin_id')) {
            $adminId = session('admin_id');
            $admin = admin::find($adminId);
            if ($admin && $admin->role === 'admin') {
                $isAdmin = true;
            }
        }

        // Also check if role in session is admin
        if (session()->has('role') && session('role') === 'admin') {
            $isAdmin = true;
        }

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses CRUD Data Siswa.');
        }

        return $next($request);
    }
}



