@extends('layouts.admin')

@section('title', 'Kelola Menu Produk')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark m-0">📋 Daftar Menu Makanan & Minuman</h3>
                <p class="text-muted small m-0">Kelola ketersediaan, edit data, atau hapus menu warkop Anda.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-success fw-bold px-4 shadow-sm">
                ➕ Tambah Menu Baru
            </a>
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
                            <th style="width: 80px;">Gambar</th>
                            <th>Nama Menu</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
                            <th class="text-center" style="width: 150px;">Status Ketersediaan</th>
                            <th class="text-center" style="width: 250px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                <div class="bg-light rounded text-center d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                                    <span style="font-size: 1.5rem;">☕</span>
                                </div>
                                @endif
                            </td>
                            <td>
                                <strong class="text-dark">{{ $product->name }}</strong>
                            </td>
                            <td class="fw-bold text-success">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="text-muted small" style="max-width: 300px; white-space: normal;">
                                {{ $product->description }}
                            </td>
                            <td class="text-center">
                                @if($product->is_available)
                                <span class="badge bg-success px-3 py-2 rounded-pill small">Tersedia / Aktif</span>
                                @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill small">Habis / Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary px-3 shadow-sm rounded-pill fw-bold">
                                            {{ $product->is_available ? '⚠️ Nonaktifkan' : '✅ Aktifkan' }}
                                        </button>
                                    </form>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary px-3 shadow-sm rounded-pill fw-bold">
                                        ✏️ Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu {{ $product->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger px-3 shadow-sm rounded-pill fw-bold">
                                            ❌ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <span style="font-size: 3rem;">🍽️</span>
                                <h5 class="fw-bold mt-3">Belum Ada Menu</h5>
                                <p class="small text-muted mb-0">Klik tombol "Tambah Menu Baru" untuk mengisi menu pertama warkop Anda.</p>
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
