<?php

namespace App\Http\Controllers\admin;

if (!defined('BASEPATH')) {
    define('BASEPATH', true);
}
defined('BASEPATH') OR exit('No direct script access allowed');

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Exception;

class peasan_langsungController extends Controller
{
    /**
     * Tampilkan halaman kasir pesan langsung.
     */
    public function index()
    {
        // Hanya memuat meja aktif dan produk yang tersedia
        $tables = Table::where('is_active', true)->get();
        $products = Product::where('is_available', true)->get();

        return view('admin.pesan_langsung.index', compact('tables', 'products'));
    }

    /**
     * Proses pembayaran langsung dan simpan transaksi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // 1. Cari atau buat customer berdasarkan nama
            $customerName = trim($request->input('customer_name')) ?: 'Pelanggan Umum';
            $customer = Customer::firstOrCreate([
                'name' => $customerName
            ]);

            // 2. Cari atau buat meja khusus kasir (Pesan Langsung / Takeaway)
            $table = Table::firstOrCreate(
                ['table_number' => 'Pesan Langsung'],
                [
                    'qr_token' => 'direct-' . strtoupper(bin2hex(random_bytes(3))),
                    'is_active' => true
                ]
            );

            // 3. Hitung total harga di sisi server demi keamanan data
            $totalPrice = 0;
            $itemsData = [];

            foreach ($request->input('items') as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemPrice = $product->price;
                $subtotal = $itemPrice * $item['quantity'];
                $totalPrice += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_at_order' => $itemPrice,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            // 4. Generate Nomor Pesanan Instan (ORD-DIR-...)
            $orderNumber = 'ORD-DIR-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));

            // 5. Simpan ke database dengan status langsung 'completed' (lunas & selesai)
            $order = Order::create([
                'customer_id' => $customer->id,
                'table_id' => $table->id,
                'cashier_id' => auth()->id(), // Mencatat kasir/admin aktif yang memproses
                'order_number' => $orderNumber,
                'total_price' => $totalPrice,
                'status' => 'completed',
            ]);

            // 5. Simpan Rincian Order Items
            foreach ($itemsData as $item) {
                $item['order_id'] = $order->id;
                \App\Models\OrderItem::create($item);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan #' . $orderNumber . ' berhasil dibayar dan diproses secara langsung!',
                'order_number' => $orderNumber
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            // Catat error asli ke log sistem untuk investigasi developer
            \Illuminate\Support\Facades\Log::error('Direct Order Store Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pesanan langsung karena terjadi kesalahan sistem.'
            ], 500);
        }
    }
}
