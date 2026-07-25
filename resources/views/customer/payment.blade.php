<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Warkop Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 80px;
        }

        .payment-card {
            border: none;
            border-radius: 15px;
        }
    </style>
</head>

<body>

    @include('customer.partials.navbar')

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm payment-card p-4">
                    <div class="text-center mb-4">
                        <span style="font-size: 40px;">💳</span>
                        <h3 class="fw-bold text-dark mt-2">Metode Pembayaran</h3>
                        <p class="text-muted small">Pesanan <strong>{{ $order->order_number }}</strong> berhasil dibuat.</p>
                        <h2 class="fw-bold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h2>
                    </div>

                    <hr class="text-muted">

                    <h5 class="fw-bold mb-3 text-secondary">Pilih Saluran Pembayaran:</h5>

                    <div class="accordion mb-4" id="paymentOptions">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#opsiKasir">
                                    💵 Bayar Langsung di Kasir
                                </button>
                            </h2>
                            <div id="opsiKasir" class="accordion-collapse collapse show" data-bs-parent="#paymentOptions">
                                <div class="accordion-body text-muted small">
                                    Silakan sebutkan nomor nota <strong class="text-dark">{{ $order->order_number }}</strong> Anda ke meja kasir saat melakukan pembayaran tunai/debit di tempat.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#opsiBRI">
                                    🏦 Transfer Bank BRI
                                </button>
                            </h2>
                            <div id="opsiBRI" class="accordion-collapse collapse" data-bs-parent="#paymentOptions">
                                <div class="accordion-body">
                                    <p class="text-muted small mb-1">Nomor Rekening Warkop:</p>
                                    <h4 class="fw-bold text-primary">0199 2818 2181 2</h4>
                                    <p class="text-muted small mb-3">Atas Nama: <strong>Warkop Digital Utama</strong></p>

                                    <hr class="text-muted">

                                    <h5 class="fw-bold mb-2 text-secondary">Kirim Bukti Transfer</h5>
                                    <p class="text-muted small mb-3">Wajib unggah foto struk/bukti transfer di bawah ini:</p>

                                    <form action="{{ route('customer.payment.upload', $order->order_number) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <input class="form-control form-control-sm" type="file" name="payment_proof" accept="image/*" required>
                                            <div class="form-text text-muted small">Format didukung: JPG, PNG, JPEG (Maksimal 2MB).</div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-success fw-bold py-2 shadow-sm">
                                                Unggah & Konfirmasi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('customer.menu') }}" class="btn btn-light btn-sm text-muted py-2 shadow-sm">
                            Kembali / Bayar Langsung di Kasir
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>