<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('patient_id')) {
            return redirect()->route('patient.login')
                ->with('error', 'Silakan login dengan No. RM dan Kode Unik Anda.');
        }
        return $next($request);
    }
}