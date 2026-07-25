@extends('layouts.admin')

@php
if (!function_exists('html_escape')) {
    function html_escape($var) {
        return e($var);
    }
}
@endphp

@section('styles')
<style>
    .card-menu {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 12px;
        cursor: pointer;
    }

    .card-menu:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .pos-cart-panel {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        position: sticky;
        top: 90px;
        border: 1px solid rgba(0,0,0,0.08);
    }

    .product-grid-container {
        max-height: calc(100vh - 180px);
        overflow-y: auto;
        padding-right: 5px;
    }

    .cart-items-list {
        max-height: calc(100vh - 430px);
        overflow-y: auto;
        padding-right: 5px;
    }

    .btn-qty {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        padding: 0;
        font-weight: bold;
    }

    /* Kustomisasi scrollbar yang rapi */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #999;
    }
</style>
@endsection

@section('content')
<div class="row g-4 mt-2">
    <!-- KOLOM KIRI: KERANJANG BELANJA & PEMBAYARAN -->
    <div class="col-lg-5 col-md-12">
        <div class="card pos-cart-panel p-3">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h4 class="fw-bold text-dark m-0">🛒 Keranjang Kasir</h4>
                <span class="badge bg-success rounded-pill px-3 py-2 text-white" id="cart-count">0 Item</span>
            </div>

            <!-- Formulir Identitas Pelanggan & Meja -->
            <div class="mb-3">
                <label for="customer_name" class="form-label fw-semibold small text-muted">Nama Pelanggan</label>
                <input type="text" class="form-control rounded-pill border-secondary-subtle" id="customer_name" placeholder="Nama Pelanggan (Opsional, Default: Pelanggan Umum)">
            </div>



            <!-- Daftar Item Belanja -->
            <div class="cart-items-list mb-3" id="cart-items-container">
                <!-- Data Keranjang di-render via Javascript -->
                <div class="text-center py-5 text-muted border border-dashed rounded-3 bg-light">
                    <span style="font-size: 2.5rem;">☕</span>
                    <p class="small m-0 mt-2">Pilih menu produk di sebelah kanan untuk menambahkan pesanan.</p>
                </div>
            </div>

            <!-- Informasi Ringkasan Transaksi -->
            <div class="border-top pt-3 bg-light p-3 rounded-3 mb-3 border">
                <div class="d-flex justify-content-between align-items-center fw-bold fs-5">
                    <span class="text-dark">Total Tagihan:</span>
                    <span class="text-success" id="grand-total-display">Rp 0</span>
                </div>
            </div>

            <!-- Tombol Kirim & Bayar -->
            <button type="button" class="btn btn-success w-100 btn-lg rounded-pill fw-bold shadow-sm" id="btn-submit-order" onclick="submitDirectOrder()" disabled>
                💳 Proses Pembayaran Langsung
            </button>
        </div>
    </div>

    <!-- KOLOM KANAN: DAFTAR MENU PRODUK -->
    <div class="col-lg-7 col-md-12">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-dark m-0">📋 Menu Warkop</h3>
                <p class="text-muted small m-0">Klik pada produk untuk menambahkannya ke keranjang belanja.</p>
            </div>
            <!-- Pencarian Produk -->
            <div style="min-width: 250px;" class="mt-2 mt-md-0">
                <input type="text" class="form-control rounded-pill border-secondary-subtle shadow-sm" id="search-product" placeholder="🔍 Cari menu produk..." onkeyup="filterProducts()">
            </div>
        </div>

        <!-- Grid Produk -->
        <div class="product-grid-container">
            <div class="row g-3" id="product-list-container">
                @forelse($products as $product)
                    <div class="col-md-6 col-sm-6 product-card-item" data-name="{{ strtolower($product->name) }}">
                        <div class="card shadow-sm h-100 card-menu overflow-hidden border border-light" onclick="addProductToCart({{ $product->id }}, '{{ html_escape($product->name) }}', {{ $product->price }})">
                            <div class="position-relative">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 140px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 140px; color: #adb5bd;">
                                        <span class="fs-1">☕</span>
                                    </div>
                                @endif
                                <span class="position-absolute top-0 end-0 bg-success text-white px-3 py-1 rounded-bl-3 m-0 small fw-semibold shadow-sm" style="border-bottom-left-radius: 12px;">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-dark m-0 text-truncate" id="product-name-{{ $product->id }}">{{ $product->name }}</h6>
                                    <p class="text-muted small mt-1 mb-0 text-truncate-2" style="font-size: 0.8rem; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        {{ $product->description ?: 'Tidak ada deskripsi.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <span style="font-size: 3rem;">🍂</span>
                        <h5 class="fw-bold text-muted mt-3">Produk Tidak Ditemukan</h5>
                        <p class="small text-muted">Belum ada menu produk aktif yang terdaftar di database.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Alert Konfirmasi Pemrosesan -->
<div class="modal fade" id="successModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-body text-center p-4">
                <span class="display-3 text-success">✔️</span>
                <h4 class="fw-bold text-dark mt-3">Pembayaran Berhasil!</h4>
                <p class="text-muted small" id="modal-success-message">Pesanan telah berhasil dicatat dan diselesaikan oleh Kasir.</p>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-success rounded-pill px-4">Lihat Daftar Pesanan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // State Keranjang Belanja Kasir
    let cart = {};

    /**
     * Menambahkan produk ke keranjang kasir
     */
    function addProductToCart(id, name, price) {
        if (cart[id]) {
            cart[id].quantity += 1;
        } else {
            cart[id] = {
                product_id: id,
                name: name,
                price: price,
                quantity: 1,
                notes: ''
            };
        }
        renderCart();
    }

    /**
     * Mengubah kuantitas item (tambah / kurang)
     */
    function updateQty(id, delta) {
        if (!cart[id]) return;
        
        cart[id].quantity += delta;
        if (cart[id].quantity <= 0) {
            delete cart[id];
        }
        renderCart();
    }

    /**
     * Menghapus item dari keranjang
     */
    function removeItem(id) {
        delete cart[id];
        renderCart();
    }

    /**
     * Memperbarui catatan item belanja
     */
    function updateNotes(id, value) {
        if (cart[id]) {
            cart[id].notes = value;
        }
    }

    /**
     * Mengatur pencarian menu produk
     */
    function filterProducts() {
        let query = document.getElementById('search-product').value.toLowerCase().trim();
        let items = document.querySelectorAll('.product-card-item');

        items.forEach(item => {
            let name = item.getAttribute('data-name');
            if (name.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    /**
     * Me-render keranjang belanja ke layar
     */
    function renderCart() {
        let container = document.getElementById('cart-items-container');
        let keys = Object.keys(cart);
        let grandTotal = 0;
        let totalItems = 0;

        // Reset keranjang jika kosong
        if (keys.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted border border-dashed rounded-3 bg-light">
                    <span style="font-size: 2.5rem;">☕</span>
                    <p class="small m-0 mt-2">Pilih menu produk di sebelah kanan untuk menambahkan pesanan.</p>
                </div>
            `;
            document.getElementById('grand-total-display').innerText = 'Rp 0';
            document.getElementById('cart-count').innerText = '0 Item';
            document.getElementById('btn-submit-order').disabled = true;
            return;
        }

        // Tampilkan tombol submit & hitung jumlah item
        document.getElementById('btn-submit-order').disabled = false;
        container.innerHTML = '';

        keys.forEach(id => {
            let item = cart[id];
            let itemSubtotal = item.price * item.quantity;
            grandTotal += itemSubtotal;
            totalItems += item.quantity;

            let card = document.createElement('div');
            card.className = 'border rounded-3 p-3 bg-light mb-3 position-relative';
            card.innerHTML = `
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" style="font-size: 0.8rem;" onclick="removeItem(${id})"></button>
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div style="max-width: 75%;">
                        <strong class="text-dark d-block text-truncate small">${item.name}</strong>
                        <span class="text-muted small">Rp ${item.price.toLocaleString('id-ID')} / porsi</span>
                    </div>
                    <span class="fw-bold text-success small">Rp ${itemSubtotal.toLocaleString('id-ID')}</span>
                </div>
                <!-- Catatan Tambahan -->
                <div class="mb-2">
                    <input type="text" class="form-control form-control-sm border-secondary-subtle rounded" 
                        placeholder="Tambahkan catatan (cth: es sedikit)..." 
                        value="${item.notes}" 
                        onchange="updateNotes(${id}, this.value)">
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Kuantitas:</span>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-qty btn-outline-secondary" onclick="updateQty(${id}, -1)">-</button>
                        <span class="fw-bold px-1">${item.quantity}</span>
                        <button type="button" class="btn btn-qty btn-outline-secondary" onclick="updateQty(${id}, 1)">+</button>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });

        document.getElementById('grand-total-display').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        document.getElementById('cart-count').innerText = totalItems + ' Item';
    }

    /**
     * Mengirim pesanan langsung kasir ke server via AJAX
     */
    function submitDirectOrder() {
        let customerName = document.getElementById('customer_name').value.trim();
        let itemsArray = Object.values(cart);

        // Validasi client-side
        if (itemsArray.length === 0) {
            alert('Keranjang masih kosong.');
            return;
        }

        let submitBtn = document.getElementById('btn-submit-order');
        submitBtn.disabled = true;
        submitBtn.innerText = '⏳ Memproses Pembayaran...';

        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('admin.direct_order.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                customer_name: customerName,
                items: itemsArray
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Bersihkan data form & state
                cart = {};
                document.getElementById('customer_name').value = '';
                renderCart();

                // Tampilkan sukses modal
                document.getElementById('modal-success-message').innerText = data.message;
                let successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            } else {
                alert('Gagal memproses transaksi: ' + data.message);
            }
        })
        .catch(error => {
            console.error("AJAX Error:", error);
            alert('Terjadi kesalahan jaringan saat memproses pesanan langsung.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = '💳 Proses Pembayaran Langsung';
        });
    }
</script>
@endsection
