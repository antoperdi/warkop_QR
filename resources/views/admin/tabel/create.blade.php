@extends('layouts.admin')

@section('title', 'Tambah Meja Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.tabel.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3 shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-weight: bold; text-decoration: none;">
                ←
            </a>
            <h3 class="fw-bold text-dark m-0">➕ Tambah Meja Baru</h3>
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
            <form action="{{ route('admin.tabel.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="table_number" class="form-label small fw-bold text-secondary">Nomor / Nama Meja</label>
                    <input type="text" class="form-control @error('table_number') is-invalid @enderror" id="table_number" name="table_number" value="{{ old('table_number') }}" placeholder="Contoh: Meja 01 atau Meja VIP 1" required autofocus>
                    <div class="form-text text-muted small">Nama meja harus unik agar tidak membingungkan kasir saat menyajikan hidangan.</div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success fw-bold py-2 shadow-sm">
                        Simpan Meja
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
