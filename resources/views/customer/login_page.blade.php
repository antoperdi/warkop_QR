<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Warkop Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 90px;
        }

        .login-card {
            border: none;
            border-radius: 15px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">☕ Warkop Digital</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 text-center">

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card shadow-sm login-card p-4 my-4">
                    <div class="card-body">
                        <div class="mb-4">
                            <span style="font-size: 50px;">☕</span>
                            <h3 class="fw-bold text-dark mt-2">Selamat Datang</h3>
                            <p class="text-muted small">Silakan masuk terlebih dahulu untuk melihat daftar menu dan memesan hidangan langsung dari meja Anda.</p>
                        </div>

                        <hr class="text-muted my-4">

                        <div class="d-grid gap-2">
                            <a href="{{ route('google.login') }}" class="btn btn-lg btn-primary d-flex align-items-center justify-content-center fw-semibold py-2 shadow-sm">
                                <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.24 10.285V13.4h6.887C18.2 15.614 15.645 18 12.24 18c-3.86 0-7-3.14-7-7s3.14-7 7-7c1.71 0 3.275.61 4.5 1.625l2.437-2.437C17.312 1.696 14.933 1 12.24 1 6.576 1 2 5.576 2 11.24s4.576 10.24 10.24 10.24c5.795 0 10.254-4.074 10.254-10.24 0-.695-.08-1.355-.22-1.955H12.24z" />
                                </svg>
                                Masuk dengan Google
                            </a>
                        </div>
                    </div>
                </div>

                <p class="text-muted small mt-4">&copy; 2026 Warkop Digital. All Rights Reserved.</p>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>