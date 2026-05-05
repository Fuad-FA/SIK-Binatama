<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirstLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->must_change_password) {
            // Izinkan akses ke halaman ganti password saja
            if (!$request->routeIs('password.change') &&
                !$request->routeIs('password.update')) {
                return redirect()->route('password.change')
                    ->with('warning', 'Anda harus mengganti password terlebih dahulu.');
            }
        }

        return $next($request);
    }
}