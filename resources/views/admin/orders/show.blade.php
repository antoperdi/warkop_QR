@extends('layouts.admin')

@section('title', 'Detail Pesanan Pelanggan')

@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3 shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-weight: bold; text-decoration: none;">
                ←
            </a>
            <h3 class="fw-bold text-dark m-0">Detail Pesanan #{{ $order->order_number }}</h3>
        </div>

        <div class="card shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2 text-secondary">📋 Rincian Produk</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light small fw-bold">
                        <tr>
                            <th>Menu</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong class="text-dark">
                                    {{ $item->product->name ?? 'Menu Lain' }}
                                </strong>
                                @if($item->notes)
                                <div class="text-muted small mt-1">📝 Catatan: "{{ $item->notes }}"</div>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end text-muted">Rp {{ number_format($item->price_at_order, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-light">
                            <td colspan="3" class="fw-bold text-end">Total Pembayaran:</td>
                            <td class="text-end fw-bold fs-5 text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm p-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2 text-secondary">👤 Informasi Pelanggan</h5>
            <div class="row">
                <div class="col-sm-6 mb-2">
                    <span class="text-muted small d-block">Nama Pelanggan:</span>
                    <strong class="text-dark">{{ $order->customer->name ?? 'Tamu Google' }}</strong>
                </div>
                <div class="col-sm-6 mb-2">
                    <span class="text-muted small d-block">Email Google:</span>
                    <strong class="text-dark">{{ $order->customer->email ?? '-' }}</strong>
                </div>
                <div class="col-sm-6 mb-2">
                    <span class="text-muted small d-block">Nomor Meja:</span>
                    <strong class="text-dark">{{ $order->table->table_number ?? 'Tanpa Meja' }}</strong>
                </div>
                <div class="col-sm-6 mb-2">
                    <span class="text-muted small d-block">Tanggal Transaksi:</span>
                    <strong class="text-dark">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</strong>
                </div>
                @if($order->cashier)
                <div class="col-sm-6 mb-2">
                    <span class="text-muted small d-block">Diinput oleh Kasir:</span>
                    <strong class="text-success">{{ $order->cashier->name }}</strong>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2 text-secondary">⚙️ Kelola Status Pesanan</h5>
            
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="status" class="form-label small fw-bold text-secondary">Ubah Status Menjadi:</label>
                    <select class="form-select" id="status" name="status">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Belum Bayar (Pending)</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Verifikasi Pembayaran / Diproses</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai Disajikan (Completed)</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Batalkan Pesanan (Cancelled)</option>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success fw-bold py-2 shadow-sm">
                        Perbarui Status Pesanan
                    </button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm p-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2 text-secondary">📸 Bukti Transfer Pembayaran</h5>
            @if($order->payment_proof)
            <div class="text-center mt-3">
                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer Pelanggan" class="img-fluid rounded shadow-sm border" style="max-height: 400px; object-fit: contain;">
                <div class="mt-3">
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-bold">
                        🔍 Lihat Ukuran Penuh
                    </a>
                </div>
            </div>
            @else
            <div class="text-center text-muted py-5">
                <span style="font-size: 2.5rem;">📄</span>
                <p class="small mt-2 mb-0">Belum ada bukti transfer pembayaran yang diunggah oleh pelanggan.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
