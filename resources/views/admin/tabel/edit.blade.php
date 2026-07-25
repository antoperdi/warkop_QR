@extends('layouts.admin')

@section('title', 'Edit Meja Warkop')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.tabel.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3 shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-weight: bold; text-decoration: none;">
                ←
            </a>
            <h3 class="fw-bold text-dark m-0">✏️ Edit Meja</h3>
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
            <form action="{{ route('admin.tabel.update', $table->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="table_number" class="form-label small fw-bold text-secondary">Nomor / Nama Meja</label>
                    <input type="text" class="form-control @error('table_number') is-invalid @enderror" id="table_number" name="table_number" value="{{ old('table_number', $table->table_number) }}" placeholder="Contoh: Meja 01" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary d-block">Token QR Saat Ini</label>
                    <code class="bg-light text-danger px-2 py-1 rounded small fw-bold">{{ $table->qr_token }}</code>
                    <div class="form-text text-muted small mt-1">Token QR dibuat otomatis saat pembuatan meja pertama kali dan bersifat permanen.</div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $table->is_active ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-secondary" for="is_active">Aktifkan Meja (Bisa Digunakan Pelanggan)</label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success fw-bold py-2 shadow-sm">
                        Perbarui Meja
                    </button>
                    <a href="{{ route('admin.tabel.index') }}" class="btn btn-light btn-sm text-muted py-2 shadow-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
