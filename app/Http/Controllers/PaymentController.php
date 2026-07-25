<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPaymentProofRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Exception;

class PaymentController extends Controller
{
    // Menampilkan halaman pembayaran
    public function show($order_number)
    {
        // Cari data pesanan berdasarkan nomor nota
        $order = Order::where('order_number', $order_number)->firstOrFail();

        // VALIDASI KEAMANAN: Pastikan pelanggan yang login adalah pemilik pesanan ini
        if ($order->customer_id !== Auth::guard('customer')->id()) {
            abort(403, 'Akses Ditolak: Anda tidak berhak melihat rincian pembayaran pesanan ini.');
        }

        return view('customer.payment', compact('order'));
    }

    // Mengunggah bukti pembayaran
    public function uploadProof(UploadPaymentProofRequest $request, $order_number)
    {
        try {
            $order = Order::where('order_number', $order_number)->firstOrFail();

            // VALIDASI KEAMANAN: Pastikan pelanggan yang login adalah pemilik pesanan ini
            if ($order->customer_id !== Auth::guard('customer')->id()) {
                abort(403, 'Akses Ditolak: Anda tidak berhak mengunggah bukti pembayaran pesanan ini.');
            }

            if ($request->hasFile('payment_proof')) {
                // Simpan gambar ke folder storage/app/public/bukti_transfer
                $path = $request->file('payment_proof')->store('bukti_transfer', 'public');

                // Simpan nama path berkas bukti transfer ke database dan ubah status menjadi processing
                $order->update([
                    'payment_proof' => $path,
                    'status' => 'processing',
                ]);
            }

            return redirect()->route('customer.menu')->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu konfirmasi pesanan Anda.');

        } catch (Exception $e) {
            // Catat ke log server internal demi keamanan
            \Illuminate\Support\Facades\Log::error('Upload Payment Proof Failed: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return redirect()->route('customer.menu')->with('error', 'Gagal mengunggah bukti pembayaran karena terjadi kesalahan sistem.');
        }
    }
}
