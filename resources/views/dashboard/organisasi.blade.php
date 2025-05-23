<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Organisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
        }
        .container {
            max-width: 600px;
            margin-top: 80px;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .btn-custom {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="container text-center">
        <h1 class="mb-4">👋 Selamat Datang di Dashboard Organisasi</h1>

        
        <form action="{{ route('organisasi.request.read') }}" method="GET">
            <button type="submit" class="btn btn-success btn-custom">
                🤝 Lihat / Buat Request Donasi
            </button>
        </form>
    </div>

</body>
</html>