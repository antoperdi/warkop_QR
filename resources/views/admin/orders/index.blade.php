@extends('layouts.admin')

@section('title', 'Daftar Pesanan Pelanggan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="mb-4">
            <h3 class="fw-bold text-dark m-0">🛍️ Manajemen Pesanan Pelanggan</h3>
            <p class="text-muted small m-0">Verifikasi bukti transfer, pantau status hidangan, atau konfirmasi pesanan selesai.</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card shadow-sm p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-secondary small fw-bold">
                        <tr>
                            <th>Nomor Nota</th>
                            <th>Tanggal & Waktu</th>
                            <th>Nama Customer</th>
                            <th>Meja</th>
                            <th class="text-end">Total Bayar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Bukti Transfer</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $order->order_number }}</strong>
                            </td>
                            <td class="small text-muted">
                                {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                            </td>
                            <td>
                                {{ $order->customer->name ?? 'Tamu Google' }}
                                @if($order->cashier)
                                    <small class="d-block text-muted mt-1">(Kasir: <strong>{{ $order->cashier->name }}</strong>)</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">
                                    {{ $order->table->table_number ?? 'Tanpa Meja' }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-success">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill small">Belum Bayar</span>
                                @elseif($order->status === 'processing')
                                    <span class="badge bg-info text-white px-3 py-2 rounded-pill small">Verifikasi Kasir</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-success text-white px-3 py-2 rounded-pill small">Selesai / Disajikan</span>
                                @else
                                    <span class="badge bg-danger text-white px-3 py-2 rounded-pill small">Batal</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($order->payment_proof)
                                    <span class="badge bg-primary px-3 py-2 rounded-pill small">Ada Bukti Unggah</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-success fw-bold px-3 rounded-pill shadow-sm">
                                    🔍 Detail Pesanan
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <span style="font-size: 3rem;">🛍️</span>
                                <h5 class="fw-bold mt-3">Belum Ada Pesanan</h5>
                                <p class="small text-muted mb-0">Pesanan dari pemindaian meja pelanggan akan tampil secara otomatis di sini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
