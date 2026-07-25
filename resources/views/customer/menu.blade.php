<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Warkop Digital</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 80px;
        }

        .card-menu {
            transition: transform 0.2s;
            border: none;
            border-radius: 12px;
        }

        .card-menu:hover {
            transform: scale(1.02);
        }

        .cart-panel {
            background: white;
            border-radius: 15px;
            position: sticky;
            top: 90px;
        }
    </style>
</head>

<body>

    @include('customer.partials.navbar')

    <div class="container">
        <div class="row g-4">

            <div class="col-lg-8">
                <h3 class="fw-bold mb-4 text-success">Silakan Pilih Menu</h3>
                <div class="row g-3">

                    @forelse($products as $product)
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100 card-menu overflow-hidden">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 160px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="fw-bold m-0" id="name-{{ $product->id }}">{{ $product->name }}</h5>
                                    <p class="text-muted small my-2">{{ $product->description }}</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="fw-bold text-success fs-5">Rp <span id="price-{{ $product->id }}">{{ $product->price }}</span></span>
                                    <button class="btn btn-success px-3 btn-sm shadow-sm" onclick="addToCart({{ $product->id }})">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <span style="font-size: 3rem;">☕</span>
                        <h5 class="fw-bold text-muted mt-3">Menu Tidak Tersedia</h5>
                        <p class="small text-muted">Mohon maaf, saat ini belum ada pilihan menu yang aktif.</p>
                    </div>
                    @endforelse

                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm cart-panel p-3">
                    <h4 class="fw-bold text-dark border-bottom pb-2 mb-3">🛒 Pesanan Saya</h4>

                    <div id="cart-items-container" class="mb-3" style="max-height: 250px; overflow-y: auto;">
                        <p class="text-muted text-center my-4" id="empty-cart-msg">Keranjang masih kosong.</p>
                    </div>

                    <div class="border-top pt-2">
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                            <span>Total Bayar:</span>
                            <span class="text-success">Rp <span id="grand-total">0</span></span>
                        </div>
                        <button class="btn btn-success w-100 btn-lg fw-bold shadow" id="btn-checkout" onclick="submitOrder()" disabled>
                            Pesan Sekarang
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        let cart = {};

        function addToCart(productId) {
            let name = document.getElementById(`name-${productId}`).innerText;
            let price = parseInt(document.getElementById(`price-${productId}`).innerText);

            if (cart[productId]) {
                cart[productId].quantity += 1;
            } else {
                cart[productId] = {
                    product_id: productId,
                    name: name,
                    price: price,
                    quantity: 1
                };
            }
            renderCart();
        }

        function changeQty(productId, amount) {
            if (!cart[productId]) return;
            cart[productId].quantity += amount;
            if (cart[productId].quantity <= 0) {
                delete cart[productId];
            }
            renderCart();
        }

        function removeFromCart(productId) {
            delete cart[productId];
            renderCart();
        }

        function renderCart() {
            let container = document.getElementById('cart-items-container');
            let grandTotal = 0;
            container.innerHTML = '';

            let keys = Object.keys(cart);
            if (keys.length === 0) {
                container.innerHTML = '<p class="text-muted text-center my-4" id="empty-cart-msg">Keranjang masih kosong.</p>';
                document.getElementById('grand-total').innerText = '0';
                document.getElementById('btn-checkout').disabled = true;
                return;
            }

            document.getElementById('btn-checkout').disabled = false;

            keys.forEach(id => {
                let item = cart[id];
                let itemTotal = item.price * item.quantity;
                grandTotal += itemTotal;

                let row = document.createElement('div');
                row.className = 'd-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded shadow-sm';
                row.innerHTML = `
                    <div style="max-width: 60%;">
                        <span class="fw-bold d-block text-truncate small">${item.name}</span>
                        <span class="text-muted small">@ Rp ${item.price.toLocaleString('id-ID')}</span>
                        <span class="d-block text-success fw-bold small">Total: Rp ${itemTotal.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-xs btn-outline-secondary px-2 py-0 me-1 fw-bold" onclick="changeQty(${id}, -1)">-</button>
                        <span class="fw-bold px-2">${item.quantity}</span>
                        <button class="btn btn-xs btn-outline-secondary px-2 py-0 me-2 fw-bold" onclick="changeQty(${id}, 1)">+</button>
                        <button class="btn btn-sm btn-danger px-2 py-1" onclick="removeFromCart(${id})">❌</button>
                    </div>
                `;
                container.appendChild(row);
            });

            document.getElementById('grand-total').innerText = grandTotal.toLocaleString('id-ID');
        }

        function submitOrder() {
            let itemsArray = Object.values(cart);
            if (itemsArray.length === 0) return;

            if (!confirm('Apakah Anda yakin ingin mengirim pesanan ini?')) return;

            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('customer.order.submit') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        items: itemsArray
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cart = {}; // Kosongkan keranjang setelah berhasil
                        renderCart();
                        // Redirect secara dinamis menggunakan route helper Laravel
                        let paymentUrl = "{{ route('customer.payment', 'TEMP_ORDER_NUM') }}";
                        window.location.href = paymentUrl.replace('TEMP_ORDER_NUM', data.order_number);
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert('Terjadi kesalahan jaringan.');
                });
        }
    </script>
</body>

</html>