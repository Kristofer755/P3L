<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Pegawai;
use App\Models\Organisasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginSessionController extends Controller
{
    // ✅ Tampilkan form login (Blade)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ✅ Proses login dengan session
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Cek di tabel pembeli
        $pembeli = Pembeli::where('email', $email)->where('password', $password)->first();
        if ($pembeli) {
            Session::put('user', $pembeli);
            return redirect()->route('dashboard.pembeli');
        }

        // Cek di tabel penitip
        $penitip = Penitip::where('email', $email)->where('password', $password)->first();
        if ($penitip) {
            Session::put('user', $penitip);
            return redirect()->route('dashboard.penitip');
        }

        // Cek di tabel pegawai
        $pegawai = Pegawai::where('email', $email)->where('password', $password)->first();
        if ($pegawai) {
            Session::put('user', $pegawai);

            // Cek role jabatan
            $jabatan = strtolower($pegawai->jabatan);

            if ($jabatan == 'admin') {
                return redirect()->route('dashboard.admin');
            } elseif ($jabatan == 'pegawai gudang') {
                return redirect()->route('dashboard.gudang');
            } elseif ($jabatan == 'owner') {
                return redirect()->route('dashboard.owner');
            } elseif ($jabatan == 'customer service') {
                return redirect()->route('dashboard.cs');
            }
        }

        // Cek di tabel organisasi
        $organisasi = Organisasi::where('email', $email)->where('password', $password)->first();
        if ($organisasi) {
            Session::put('user', $organisasi);
            return redirect()->route('dashboard.organisasi');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    // ✅ Tampilkan halaman dashboard (hanya jika sudah login)
    public function dashboard()
    {
        return view('dashboard', [
            'user' => Auth::user(),
        ]);
    }

    // ✅ Logout
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session + regenerate token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'judul_diskusi' => 'required|string',
        ]);

        $user = Session::get('user');
    
        $diskusi = DiskusiProduk::create([
            'id_barang' => $request->id_barang,
            'id_pembeli' => $user->id_pembeli, // karena hanya pembeli yang bisa buat diskusi
            'judul_diskusi' => $request->judul_diskusi,
        ]);
    
        return redirect()->route('diskusi.show', $diskusi->id_diskusi);
    }
    

    public function kirimPesan(Request $request)
    {
        $request->validate([
            'id_diskusi' => 'required',
            'pesan' => 'required|string',
        ]);
    
        $user = Session::get('user');
    
        // Cek apakah user adalah pembeli atau pegawai (CS)
        if (property_exists($user, 'id_pembeli')) {
            $senderType = 'pembeli';
            $senderId = $user->id_pembeli;
        } elseif (property_exists($user, 'id_pegawai')) {
            $senderType = 'pegawai';
            $senderId = $user->id_pegawai;
        } else {
            return back()->with('error', 'Role tidak dikenali');
        }
    
        PesanDiskusi::create([
            'id_diskusi' => $request->id_diskusi,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'pesan' => $request->pesan,
        ]);
    
        return back()->with('success', 'Pesan dikirim!');
    }
    
}
