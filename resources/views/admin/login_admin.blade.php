<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Warkop Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            background-color: #ffffff;
            overflow: hidden;
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            background: linear-gradient(135deg, #198754, #145c32);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }

        .btn-login {
            background-color: #198754;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            padding: 12px;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #145c32;
            transform: translateY(-2px);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.25);
            border-color: #198754;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="login-header">
            <h3 class="fw-bold m-0">☕ Panel Admin</h3>
            <p class="small text-white-50 m-0 mt-1">Warkop Digital Utama</p>
        </div>
        <div class="card-body p-4">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show small" role="alert" style="border-radius: 10px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show small" role="alert" style="border-radius: 10px;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show small" role="alert" style="border-radius: 10px;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold text-secondary">Alamat Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="admin@warkop.com" required autofocus autocomplete="email">
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label small fw-bold text-secondary">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required autocomplete="current-password">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-login shadow-sm">
                        Masuk Sekarang
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('customer.menu') }}" class="text-decoration-none text-muted small">Kembali ke Menu Pelanggan</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
