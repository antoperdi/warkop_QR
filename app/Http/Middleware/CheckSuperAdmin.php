<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSuperAdmin
{
    /**
     * Pastikan hanya Super Admin yang dapat mengakses rute tertentu.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'super_admin') {
            return $next($request);
        }

        // Gagalkan akses jika bukan super_admin
        abort(403, 'Akses Ditolak: Halaman ini khusus untuk peran Super Admin.');
    }
}
