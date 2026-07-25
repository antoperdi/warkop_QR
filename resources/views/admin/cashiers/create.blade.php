@extends('layouts.admin')

@section('title', 'Daftarkan Kasir Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.cashiers.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3 shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-weight: bold; text-decoration: none;">
                ←
            </a>
            <h3 class="fw-bold text-dark m-0">👥 Daftarkan Kasir Baru</h3>
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
            <form action="{{ route('admin.cashiers.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label small fw-bold text-secondary">Nama Lengkap Staf</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Rian Ramadhan" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold text-secondary">Alamat Email Login</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Contoh: rian@gmail.com" required>
                    <div class="form-text text-muted small">Email ini digunakan staf kasir untuk masuk ke dalam dashboard.</div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label small fw-bold text-secondary">Kata Sandi (Password)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success fw-bold py-2 shadow-sm">
                        Daftarkan Staf Baru
                    </button>
                    <a href="{{ route('admin.cashiers.index') }}" class="btn btn-light btn-sm text-muted py-2 shadow-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
