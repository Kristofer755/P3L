<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;

class MobileLoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $email = $credentials['email'];
        $password = $credentials['password'];

        // Pembeli
        $pembeli = Pembeli::where('email', $email)->first();
        if ($pembeli && $pembeli->password === $password) {
            return response()->json([
                'message' => 'Login berhasil sebagai pembeli',
                'role' => 'pembeli',
                'user' => $pembeli
            ]);
        }

        // Penitip
        $penitip = Penitip::where('email', $email)->first();
        if ($penitip && $penitip->password === $password) {
            return response()->json([
                'message' => 'Login berhasil sebagai penitip',
                'role' => 'penitip',
                'user' => $penitip
            ]);
        }

        // Pegawai
        $pegawai = Pegawai::where('email', $email)->first();
        if ($pegawai && $pegawai->password === $password) {
            $jabatan = strtolower($pegawai->jabatan);
            if (in_array($jabatan, ['kurir', 'hunter'])) {
                return response()->json([
                    'message' => 'Login berhasil sebagai pegawai',
                    'role' => 'pegawai',
                    'jabatan' => $jabatan,
                    'user' => $pegawai
                ]);
            }
        }

        return response()->json([
            'message' => 'Email atau password salah'
        ], 401);
    }
}
