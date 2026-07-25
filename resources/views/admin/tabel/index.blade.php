@extends('layouts.admin')

@section('title', 'Kelola Meja Warkop')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark m-0">🌐 Daftar Meja Warkop</h3>
                <p class="text-muted small m-0">Kelola nomor meja, pantau token QR, aktifkan/nonaktifkan meja pelanggan.</p>
            </div>
            <a href="{{ route('admin.tabel.create') }}" class="btn btn-success fw-bold px-4 shadow-sm">
                ➕ Tambah Meja Baru
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
                            <th>Nomor / Nama Meja</th>
                            <th>Token QR</th>
                            <th>Tautan Akses Meja (Link Scan)</th>
                            <th class="text-center" style="width: 150px;">Status</th>
                            <th class="text-center" style="width: 250px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tables as $table)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $table->table_number }}</strong>
                            </td>
                            <td>
                                <code class="bg-light text-danger px-2 py-1 rounded small fw-bold">{{ $table->qr_token }}</code>
                            </td>
                            <td>
                                <div class="input-group input-group-sm" style="max-width: 320px;">
                                    <input type="text" class="form-control bg-light" id="link-{{ $table->id }}" value="{{ url('/table/' . $table->qr_token) }}" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyLink({{ $table->id }})">📋 Salin</button>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($table->is_active)
                                <span class="badge bg-success px-3 py-2 rounded-pill small">Aktif</span>
                                @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill small">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.tabel.toggle', $table->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary px-3 shadow-sm rounded-pill fw-bold">
                                            {{ $table->is_active ? '⚠️ Nonaktifkan' : '✅ Aktifkan' }}
                                        </button>
                                    </form>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.tabel.edit', $table->id) }}" class="btn btn-sm btn-primary px-3 shadow-sm rounded-pill fw-bold">
                                        ✏️ Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.tabel.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ $table->table_number }}?');">
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
                                <span style="font-size: 3rem;">🪑</span>
                                <h5 class="fw-bold mt-3">Belum Ada Meja</h5>
                                <p class="small text-muted mb-0">Klik tombol "Tambah Meja Baru" untuk mengisi meja pertama warkop Anda.</p>
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

@section('scripts')
<script>
    function copyLink(id) {
        let copyText = document.getElementById("link-" + id);
        copyText.select();
        copyText.setSelectionRange(0, 99999); // Untuk HP
        navigator.clipboard.writeText(copyText.value);
        alert("Link scan meja berhasil disalin: " + copyText.value);
    }
</script>
@endsection
