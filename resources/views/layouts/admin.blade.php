<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 80px;
        }

        .admin-navbar {
            background: linear-gradient(135deg, #198754, #145c32);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px 12px;
        }

        .btn {
            border-radius: 8px;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
    @yield('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark admin-navbar fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('admin.products.index') }}">☕ Admin Warkop</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.direct_order.*') ? 'active fw-bold text-white' : 'text-white-50' }}" href="{{ route('admin.direct_order.index') }}">💻 Pesan Langsung</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.orders.*') ? 'active fw-bold text-white' : 'text-white-50' }}" href="{{ route('admin.orders.index') }}">🛍️ Kelola Pesanan</a>
                    </li>
                    @if(auth()->user()->role === 'super_admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ Route::is('admin.products.*') || Route::is('admin.tabel.*') || Route::is('admin.cashiers.*') ? 'active fw-bold text-white' : 'text-white-50' }}" href="#" id="warkopDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            ⚙️ Kelola Warkop
                        </a>
                        <ul class="dropdown-menu shadow border-0 rounded-3 mt-2" aria-labelledby="warkopDropdown">
                            <li>
                                <a class="dropdown-item py-2 {{ Route::is('admin.products.*') ? 'active fw-bold bg-success text-white' : '' }}" href="{{ route('admin.products.index') }}">📋 Kelola Menu</a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 {{ Route::is('admin.tabel.*') ? 'active fw-bold bg-success text-white' : '' }}" href="{{ route('admin.tabel.index') }}">🌐 Kelola Meja</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item py-2 {{ Route::is('admin.cashiers.*') ? 'active fw-bold bg-success text-white' : '' }}" href="{{ route('admin.cashiers.index') }}">👥 Kelola Staf</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                </ul>
                <div class="d-flex align-items-center text-white">
                    <span class="me-3 small d-none d-sm-inline">Masuk sebagai: <strong>{{ auth()->user()->name }}</strong></span>
                    <a href="{{ route('admin.logout') }}" class="btn btn-sm btn-outline-light px-3 rounded-pill shadow-sm">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>