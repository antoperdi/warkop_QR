@extends('layouts.admin')

@section('title', 'Edit Menu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3 shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-weight: bold; text-decoration: none;">
                ←
            </a>
            <h3 class="fw-bold text-dark m-0">✏️ Edit Menu</h3>
        </div>

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small shadow-sm" role="alert" style="border-radius: 10px;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show small shadow-sm" role="alert" style="border-radius: 10px;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card shadow-sm p-4">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label small fw-bold text-secondary">Nama Menu</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" placeholder="Contoh: Kopi Susu Aren" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label small fw-bold text-secondary">Harga Menu (Rp)</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" placeholder="Contoh: 12000" min="0" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-bold text-secondary">Deskripsi Menu</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Deskripsikan komposisi atau keunggulan menu..." required>{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary d-block">Foto Menu Saat Ini</label>
                    @if($product->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded shadow-sm img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    @else
                    <p class="text-muted small">Belum ada foto.</p>
                    @endif

                    <label for="image" class="form-label small fw-bold text-secondary">Unggah Foto Baru (Opsional)</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    <div class="form-text text-muted small">Meninggalkan bagian ini kosong jika tidak ingin memperbarui foto. Maksimal 2MB.</div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-secondary" for="is_available">Aktifkan Menu (Tampil di Pelanggan)</label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success fw-bold py-2 shadow-sm">
                        Perbarui Menu
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm text-muted py-2 shadow-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
