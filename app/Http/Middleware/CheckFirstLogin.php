<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirstLogin
{
    // public function handle(Request $request, Closure $next): Response
    // {
    //     $user = auth()->user();

    //     if ($user && $user->must_change_password) {
    //         // Izinkan akses ke halaman ganti password saja
    //         if (!$request->routeIs('password.change') &&
    //             !$request->routeIs('password.update')) {
    //             return redirect()->route('password.change')
    //                 ->with('warning', 'Anda harus mengganti password terlebih dahulu.');
    //         }
    //     }

    //     return $next($request);
    // }
//     public function handle(Request $request, Closure $next): Response
// {
//     $user = auth()->user();

//     // Admin tidak perlu ganti password
//     if ($user && $user->role === 'admin') {
//         return $next($request);
//     }

//     if ($user && $user->must_change_password) {
//         if (!$request->routeIs('password.change') &&
//             !$request->routeIs('password.update')) {
//             return redirect()->route('password.change')
//                 ->with('warning', 'Anda harus mengganti password terlebih dahulu.');
//         }
//     }

//     return $next($request);
// }
public function handle(Request $request, Closure $next): Response
{
    // Jika belum login, lanjut
    if (!auth()->check()) {
        return $next($request);
    }

    $user = auth()->user();

    // Admin tidak wajib ganti password
    if ($user->role === 'admin') {
        return $next($request);
    }

    // Guru / siswa wajib ganti password pertama kali
    if ($user->must_change_password) {

        // Hindari redirect loop
        if (
            !$request->routeIs('password.change') &&
            !$request->routeIs('password.update')
        ) {
            return redirect()
                ->route('password.change')
                ->with('warning', 'Anda harus mengganti password terlebih dahulu.');
        }
    }

    return $next($request);
}
}