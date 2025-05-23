<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            margin: 10px 5px;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #333;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        form {
            display: inline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Selamat Datang di Dashboard Admin</h1>

        <form action="{{ route('admin.organisasi.index') }}" method="get">
            <button type="submit" class="btn">Kelola Data Organisasi</button>
        </form>

        <!-- <form action="{{ route('pembeli.alamat') }}" method="get">
            <button type="submit" class="btn">Kelola Data Alamat</button>
        </form> -->

        <a href="{{ route('pegawai.resetPassword', $pegawai->id_pegawai) }}"
           onclick="return confirm('Yakin reset password ke tanggal lahir?')"
           class="btn btn-warning">Reset Password</a>
    </div>

</body>
</html>
