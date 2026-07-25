<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan formulir login admin.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/admin');
        }
        return view('admin.login_admin');
    }

    /**
     * Proses autentikasi login admin.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('/admin')->with('success', 'Selamat datang kembali, Admin!');
            }

            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin Login Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->with('error', 'Terjadi kesalahan sistem saat mencoba login. Silakan hubungi pengembang.');
        }
    }

    /**
     * Proses logout admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Anda berhasil keluar dari panel admin.');
    }
}
