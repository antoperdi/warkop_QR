<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - Warkop Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 90px;
        }

        .history-card {
            border: none;
            border-radius: 15px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table tr {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            border-radius: 8px;
        }

        .table td, .table th {
            border: none;
            padding: 12px;
            vertical-align: middle;
        }

        .table tr td:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .table tr td:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
    </style>
</head>

<body>

    @include('customer.partials.navbar')

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex align-items-center mb-4">
                    <span style="font-size: 30px;" class="me-2">📜</span>
                    <h3 class="fw-bold text-dark m-0">Riwayat Pemesanan Anda</h3>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if($orders->isEmpty())
                <div class="card shadow-sm history-card text-center p-5">
                    <div class="card-body">
                        <span style="font-size: 60px;">🍽️</span>
                        <h4 class="fw-bold text-muted mt-3">Belum Ada Pesanan</h4>
                        <p class="text-muted small mb-4">Anda belum melakukan pemesanan apa pun hari ini. Silakan pilih menu terlezat kami sekarang!</p>
                        <a href="{{ route('customer.menu') }}" class="btn btn-success fw-bold px-4 py-2 rounded-pill shadow-sm">
                            Pilih Menu Sekarang
                        </a>
                    </div>
                </div>
                @else
                <div class="card shadow-sm history-card p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="text-secondary small fw-bold">
                                <tr>
                                    <th>Nomor Nota</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Meja</th>
                                    <th>Rincian Item</th>
                                    <th class="text-end">Total Bayar</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong class="text-dark">{{ $order->order_number }}</strong>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary rounded-pill">
                                            {{ $order->table->table_number ?? 'Tanpa Meja' }}
                                        </span>
                                    </td>
                                    <td style="max-width: 250px;">
                                        <ul class="list-unstyled m-0 p-0 small">
                                            @foreach($order->items as $item)
                                            <li class="text-truncate text-muted">
                                                - {{ $item->product_id == 1 ? 'Kopi Susu' : ($item->product_id == 2 ? 'Mie Instan' : ($item->product_id == 3 ? 'Pancong Lumer' : 'Menu Lain')) }} 
                                                <strong class="text-dark">x{{ $item->quantity }}</strong>
                                            </li>
                                            @endforeach
                                        </ul>
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
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill small">Selesai</span>
                                        @else
                                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill small">Batal</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($order->status === 'pending')
                                            <a href="{{ route('customer.payment', $order->order_number) }}" class="btn btn-sm btn-success fw-bold shadow-sm px-3 rounded-pill">
                                                Bayar
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
