<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top shadow-sm">
    <div class="container">
        @php
            $activeTable = $table ?? (session()->has('active_table_id') ? \App\Models\Table::find(session('active_table_id')) : null);
        @endphp
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('customer.menu') }}">
            <span>☕ Warkop Digital</span>
            @if ($activeTable)
                <span class="badge bg-light text-success ms-2" style="font-size: 0.8rem; border-radius: 20px;">
                    {{ $activeTable->table_number }}
                </span>
            @endif
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#customerNavbar" aria-controls="customerNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="customerNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('customer.menu') || Route::is('customer.order') ? 'active fw-bold' : '' }}" href="{{ route('customer.menu') }}">
                        📋 Daftar Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('customer.history') ? 'active fw-bold' : '' }}" href="{{ route('customer.history') }}">
                        📜 Riwayat Pemesanan
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center text-white border-top border-success border-lg-0 pt-2 pt-lg-0">
                <span class="me-3 d-inline">Halo, <strong>{{ auth()->guard('customer')->user()->name }}</strong></span>
                <a href="{{ route('customer.logout') }}" class="btn btn-sm btn-outline-light px-3 rounded-pill shadow-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>
