<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekUserLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Cek apakah user tidak aktif (is_active = 0)
        if (Auth::user()->is_active == 0) {
            Auth::logout(); // Logout paksa
            return redirect('/login')->with('error', 'Akun Anda sudah tidak aktif.');
        }

        return $next($request);
    }
}
