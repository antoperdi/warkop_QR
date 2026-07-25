<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleAuthController extends Controller
{
    // 1. Mengarahkan pelanggan ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Menerima data kembali dari Google (Callback)
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah customer dengan google_id ini sudah ada di database
            $customer = Customer::where('google_id', $googleUser->id)->first();

            if (!$customer) {
                // Jika belum ada google_id, cari berdasarkan email (menghindari duplikasi unique email)
                $customer = Customer::where('email', $googleUser->email)->first();

                if ($customer) {
                    // Update data customer yang ada dengan google_id dari Google
                    $customer->update([
                        'google_id' => $googleUser->id,
                        'name' => $googleUser->name ?? $customer->name,
                    ]);
                } else {
                    // Buat customer baru jika tidak ditemukan berdasarkan google_id maupun email
                    $customer = Customer::create([
                        'google_id' => $googleUser->id,
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                    ]);
                }
            } else {
                // Update data jika customer sudah ada
                $customer->update([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                ]);
            }

            // Loginkan pelanggan menggunakan guard 'customer' yang sudah kita buat
            Auth::guard('customer')->login($customer);

            // Jika ada token meja yang disimpan di session middleware sebelumnya,
            // kembalikan pelanggan ke meja tersebut untuk memesan makanan
            if (session()->has('active_table_id')) {
                // Ambil token meja untuk redirect kembali ke halaman menu meja tersebut
                $table = \App\Models\Table::find(session('active_table_id'));
                if ($table) {
                    return redirect()->route('customer.order', ['token' => $table->qr_token]);
                }
            }

            // Jika scan QR di luar alur (akses langsung), arahkan ke halaman utama menu umum
            return redirect('/customer/menu')->with('success', 'Berhasil login via Google!');
        } catch (Exception $e) {
            // Catat log error bawaan Laravel agar bisa ditelusuri di storage/logs/laravel.log
            \Illuminate\Support\Facades\Log::error('Google Auth Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            // Jika ada error (misal koneksi atau dibatalkan user)
            return redirect()->route('customer.login')->with('error', 'Gagal login menggunakan akun Google Anda. Silakan coba kembali.');
        }
    }

    // 3. Fungsi untuk Logout Pelanggan
    public function logout()
    {
        // Keluar dari guard customer
        Auth::guard('customer')->logout();

        // Hapus semua data session customer
        session()->invalidate();
        session()->regenerateToken();

        // Lempar kembali ke halaman login utama warkop
        return redirect()->route('customer.login')->with('success', 'Berhasil logout!');
    }
}
