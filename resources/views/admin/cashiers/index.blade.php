@extends('layouts.admin')

@section('title', 'Kelola Akun Kasir')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark m-0">👥 Manajemen Akun Staf Kasir</h3>
                <p class="text-muted small m-0">Kelola akun login staf, ganti kredensial, atau tambahkan kasir warkop baru.</p>
            </div>
            <a href="{{ route('admin.cashiers.create') }}" class="btn btn-success fw-bold px-4 shadow-sm">
                ➕ Daftarkan Kasir Baru
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
                            <th>Nama Staf</th>
                            <th>Alamat Email</th>
                            <th>Hak Akses (Role)</th>
                            <th>Tanggal Terdaftar</th>
                            <th class="text-center" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cashiers as $cashier)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $cashier->name }}</strong>
                            </td>
                            <td>{{ $cashier->email }}</td>
                            <td>
                                <span class="badge bg-info text-white px-3 py-2 rounded-pill small">
                                    {{ $cashier->role }}
                                </span>
                            </td>
                            <td class="small text-muted">
                                {{ $cashier->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.cashiers.edit', $cashier->id) }}" class="btn btn-sm btn-primary px-3 shadow-sm rounded-pill fw-bold">
                                        ✏️ Edit / Reset
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.cashiers.destroy', $cashier->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun kasir {{ $cashier->name }}?');">
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
                            <td colspan="5" class="text-center text-muted py-5">
                                <span style="font-size: 3rem;">👥</span>
                                <h5 class="fw-bold mt-3">Belum Ada Akun Kasir</h5>
                                <p class="small text-muted mb-0">Klik tombol "Daftarkan Kasir Baru" untuk membuat akun staf kasir warkop Anda.</p>
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
