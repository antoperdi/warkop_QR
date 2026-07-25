<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateTableQR;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\admin\AdminAuthController;
use App\Http\Controllers\admin\OrderManagementController;
use App\Http\Controllers\admin\CashierManagementController;
use App\Http\Controllers\admin\ProductManagementController;
use App\Http\Controllers\admin\TablesManagementController;
use App\Http\Controllers\admin\peasan_langsungController;
use App\Models\Table;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

// --- UTAMA ---
Route::get('/', function () {
    return view('welcome');
});

// --- ROUTE KHUSUS PELANGGAN VIA QR ---
Route::middleware([ValidateTableQR::class])->group(function () {
    Route::get('/table/{token}', function ($token) {
        if (!auth()->guard('customer')->check()) {
            return redirect()->route('customer.login');
        }
        $table = Table::where('qr_token', $token)->first();
        // Hanya memuat produk yang berstatus tersedia
        $products = Product::where('is_available', true)->get();
        return view('customer.menu', compact('table', 'products'));
    })->name('customer.order');
});

// --- ROUTE ORDER & RIWAYAT ---
Route::post('/customer/order/submit', [OrderController::class, 'store'])->name('customer.order.submit');
Route::get('/customer/history', [OrderController::class, 'history'])->name('customer.history');

// Halaman Login Pelanggan
Route::get('/customer/login', function () {
    return view('customer.login_page');
})->name('customer.login');

// --- ROUTE AUTENTIKASI ADMIN ---
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// --- ROUTE ADMIN KASIR (DILINDUNGI AUTHENTICATION) ---
Route::middleware(['auth'])->group(function () {
    // Dashboard Admin (Mengarahkan sesuai role user)
    Route::get('/admin', function () {
        if (auth()->user()->role === 'super_admin') {
            return redirect()->route('admin.products.index');
        }
        return redirect()->route('admin.direct_order.index');
    })->name('admin.dashboard');

    Route::get('/dashboard', function () {
        return redirect('/admin');
    });

    // Rute yang Hanya Dapat Diakses oleh Super Admin
    Route::middleware(['super_admin'])->group(function () {
        // CRUD Pengelolaan Menu Produk
        Route::resource('/admin/products', ProductManagementController::class)->names([
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
        ])->parameters(['products' => 'id']);

        // Toggle Ketersediaan Menu secara Cepat
        Route::post('/admin/products/{id}/toggle', [ProductManagementController::class, 'toggleStatus'])->name('admin.products.toggle');

        // CRUD Pengelolaan Meja Warkop
        Route::resource('/admin/tabel', TablesManagementController::class)->names([
            'index' => 'admin.tabel.index',
            'create' => 'admin.tabel.create',
            'store' => 'admin.tabel.store',
            'edit' => 'admin.tabel.edit',
            'update' => 'admin.tabel.update',
            'destroy' => 'admin.tabel.destroy',
        ])->parameters(['tabel' => 'id']);

        Route::post('/admin/tabel/{id}/toggle', [TablesManagementController::class, 'toggleStatus'])->name('admin.tabel.toggle');

        // CRUD Pengelolaan Akun Kasir
        Route::resource('/admin/cashiers', CashierManagementController::class)->names([
            'index' => 'admin.cashiers.index',
            'create' => 'admin.cashiers.create',
            'store' => 'admin.cashiers.store',
            'edit' => 'admin.cashiers.edit',
            'update' => 'admin.cashiers.update',
            'destroy' => 'admin.cashiers.destroy',
        ])->parameters(['cashiers' => 'id']);
    });

    // Rute yang Dapat Diakses oleh Super Admin dan Kasir
    Route::get('/admin/orders', [OrderManagementController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{id}', [OrderManagementController::class, 'show'])->name('admin.orders.show');
    Route::post('/admin/orders/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // Kasir Pesan Langsung
    Route::get('/admin/pesan-langsung', [peasan_langsungController::class, 'index'])->name('admin.direct_order.index');
    Route::post('/admin/pesan-langsung', [peasan_langsungController::class, 'store'])->name('admin.direct_order.store');

    // Logout Admin
    Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// --- ROUTE GOOGLE AUTH ---
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::get('/customer/logout', [GoogleAuthController::class, 'logout'])->name('customer.logout');

// --- ROUTE UTAMA MENU (FALLBACK JIKA DI AKSES DIREK) ---
Route::get('/customer/menu', function () {
    if (auth()->guard('customer')->check()) {
        $tableId = session('active_table_id');
        $table = Table::find($tableId);
        // Hanya memuat produk yang berstatus tersedia
        $products = Product::where('is_available', true)->get();
        return view('customer.menu', compact('table', 'products'));
    }
    return redirect()->route('customer.login');
})->name('customer.menu');

// --- ROUTE PEMBAYARAN VIA PAYMENTCONTROLLER ---
Route::get('/customer/payment/{order_number}', [PaymentController::class, 'show'])->name('customer.payment');
Route::post('/customer/payment/{order_number}/upload', [PaymentController::class, 'uploadProof'])->name('customer.payment.upload');
