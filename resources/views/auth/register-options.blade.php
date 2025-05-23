<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pilih Registrasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom right, #4b0082, #ffffff);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        .title {
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 600;
            color: white;
            animation: fadeIn 1s ease-in-out;
        }

        .card-wrapper {
            display: flex;
            gap: 2rem;
            animation: fadeIn 1.2s ease-in-out;
        }

        .card {
            width: 220px;
            height: 220px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
            color: #4b0082;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s;
        }

        .card span {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.25);
            background: linear-gradient(to bottom right, #f8f6ff, #ffffff);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="title">INGIN REGISTRASI SEBAGAI APA?</div>

    <div class="card-wrapper">
        <a href="{{ route('register.pembeli') }}" class="card">
            <span>👤</span>
            Pembeli
        </a>
        <a href="{{ route('organisasi.form') }}" class="card">
            <span>🏢</span>
            Organisasi
        </a>
    </div>
</body>
</html>
