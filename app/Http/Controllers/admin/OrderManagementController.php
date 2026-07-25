<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Exception;

class OrderManagementController extends Controller
{
    /**
     * Tampilkan daftar seluruh pesanan pelanggan.
     */
    public function index()
    {
        // Muat data order beserta relasi customer, table, dan cashier
        $orders = Order::with(['customer', 'table', 'cashier'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan pelanggan.
     */
    public function show($id)
    {
        // Eager load items.product relasi order beserta cashier
        $order = Order::with(['customer', 'table', 'cashier', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Ubah status pesanan pelanggan.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        try {
            $order = Order::findOrFail($id);
            $order->update([
                'status' => $request->status,
            ]);

            return redirect()->route('admin.orders.index')->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diubah menjadi ' . $request->status . '!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Order Status Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->with('error', 'Gagal mengubah status pesanan karena terjadi kesalahan sistem.');
        }
    }
}
