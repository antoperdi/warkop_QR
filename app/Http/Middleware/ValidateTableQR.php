<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Table;
use Symfony\Component\HttpFoundation\Response;

class ValidateTableQR
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->route('token');
        $table = Table::where('qr_token', $token)->first();

        // 1. Cek apakah QR terdaftar di database
        if (!$table) {
            abort(404, 'QR Code Tidak Ditemukan.');
        }

        // 2. Cek apakah Super Admin mematikan akses QR meja ini
        if (!$table->is_active) {
            return response()->view('errors.qr-inactive', ['table' => $table], 403);
        }

        // Simpan data meja ke dalam request session agar bisa diakses di halaman pemesanan
        session(['active_table_id' => $table->id]);

        return $next($request);
    }
}
