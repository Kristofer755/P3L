<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Owner</title>
    <!-- Link Bootstrap 5 CDN, hapus jika sudah include di layout -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Font Awesome untuk ikon PDF -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: #f9f9fb;
            min-height: 100vh;
        }
        .dashboard-box {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 8px 24px 0 #ebebeb;
            padding: 2rem;
            max-width: 500px;
            margin: 50px auto 0 auto;
            text-align: center;
        }
        .dashboard-title {
            font-size: 2rem;
            font-weight: 700;
            color: #674ea7;
            margin-bottom: 2rem;
        }
        .dashboard-btn {
            width: 100%;
            max-width: 300px;
            margin: 12px auto;
            font-size: 1.1rem;
            padding: 12px;
        }
        .dashboard-btn i {
            margin-right: 8px;
        }
        .dashboard-btn.btn-pdf {
            background: linear-gradient(90deg, #7f53ac 0%, #647dee 100%);
            color: #fff;
            border: none;
        }
        .dashboard-btn.btn-pdf:hover {
            background: linear-gradient(90deg, #674ea7 0%, #485563 100%);
            color: #fff;
        }
        .dashboard-btn.btn-warning {
            color: #fff;
        }
        .dashboard-btn.btn-warning:hover {
            background: #e0a800;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="dashboard-box">
        <div class="dashboard-title">Selamat Datang di Dashboard Owner</div>
        
        <a href="{{ route('pegawai.resetPassword', $pegawai->id_pegawai ?? '') }}"
            onclick="return confirm('Yakin reset password ke tanggal lahir?')"
            class="btn btn-warning dashboard-btn">
            <i class="fa fa-key"></i> Reset Password Owner
        </a>
        
        <a href="{{ route('laporan.request_donasi') }}" target="_blank" class="dashboard-btn btn btn-pdf">
            <i class="fa fa-file-pdf"></i> Download Laporan Request Donasi PDF
        </a>

        <a href="{{ route('laporan.donasi') }}" target="_blank" class="dashboard-btn btn btn-pdf">
            <i class="fa fa-file-pdf"></i> Download Laporan Donasi PDF
        </a>

        
    </div>

    <div class="container mt-5">
    <h3>Download Laporan Penitip</h3>
    <form method="POST" action="{{ route('laporan.penitip') }}" target="_blank">
        @csrf
        <div class="mb-3">
            <label>Nama Penitip</label>
            <select class="form-control" name="id_penitip" required>
                <option value="">-- Pilih Penitip --</option>
                @foreach($penitipList as $penitip)
                    <option value="{{ $penitip->id_penitip }}">{{ $penitip->nama_penitip }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Bulan</label>
            <select class="form-control" name="bulan" required>
                @for($i=1; $i<=12; $i++)
                    <option value="{{ sprintf('%02d', $i) }}" {{ $bulan == sprintf('%02d', $i) ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label>Tahun</label>
            <input type="number" class="form-control" name="tahun" value="{{ $tahun }}" required>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-file-pdf"></i> Download Laporan Penitip PDF
        </button>
    </form>
</div>
</body>
</html>