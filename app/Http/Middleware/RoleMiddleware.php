<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // public function handle(Request $request, Closure $next, string ...$roles): Response
    // {
    //     if (!auth()->check()) {
    //         return redirect()->route('login')
    //             ->with('error', 'Silakan login terlebih dahulu.');
    //     }

    //     if (!in_array(auth()->user()->role, $roles)) {
    //         abort(403, 'Akses tidak diizinkan untuk role Anda.');
    //     }

    //     if (!auth()->user()->is_active) {
    //         auth()->logout();
    //         return redirect()->route('login')
    //             ->with('error', 'Akun Anda dinonaktifkan. Hubungi administrator.');
    //     }

    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next, string ...$roles): Response
{
    if (!auth()->check()) {
        return redirect()->route('login')
            ->with('error', 'Silakan login terlebih dahulu.');
    }

    $user = auth()->user();

    // Admin selalu boleh akses semua route
    if ($user->role === 'admin') {
        return $next($request);
    }

    if (!in_array($user->role, $roles)) {
        abort(403, 'Akses tidak diizinkan untuk role Anda.');
    }

    if (!$user->is_active) {
        auth()->logout();
        return redirect()->route('login')
            ->with('error', 'Akun Anda dinonaktifkan. Hubungi administrator.');
    }

    return $next($request);
}
}