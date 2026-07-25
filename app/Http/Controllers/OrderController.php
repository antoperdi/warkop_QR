<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {

        // Ambil ID meja aktif dari session middleware QR kemarin
        $tableId = session('active_table_id');
        if (!$tableId) {
            return response()->json(['success' => false, 'message' => 'Sesi meja tidak ditemukan. Silakan scan QR kembali.'], 400);
        }

        DB::beginTransaction();
        try {
            // 2. Generate Nomor Pesanan Unik (Contoh: ORD-20260709-0001)
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));

            // 3. Hitung Total Harga Keseluruhan
            $totalPrice = 0;
            foreach ($request->items as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            // 4. Simpan ke Tabel Orders
            $order = Order::create([
                'customer_id' => Auth::guard('customer')->id(),
                'table_id' => $tableId,
                'order_number' => $orderNumber,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // 5. Simpan Rincian ke Tabel OrderItems
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price_at_order' => $item['price'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Pesanan Anda berhasil dikirim ke kasir!',
                'order_number' => $orderNumber
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            // Catat error asli ke log untuk debugging internal pengembang
            \Illuminate\Support\Facades\Log::error('Order Processing Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan sistem saat memproses pesanan Anda. Silakan coba beberapa saat lagi.'
            ], 500);
        }
    }

    // Menampilkan riwayat pemesanan pelanggan yang sedang login
    public function history()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->id())
            ->with(['items'])
            ->latest()
            ->get();

        return view('customer.history', compact('orders'));
    }
}