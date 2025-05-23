<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index()
    {
        $allPegawai = Pegawai::all();
        return response()->json($allPegawai);
    }

    public function resetPassword($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $allowedRoles = ['Admin', 'Owner', 'Customer Service', 'Pegawai Gudang'];
        if (!in_array($pegawai->jabatan, $allowedRoles)) {
            return redirect()->back()->with('error', 'Role tidak diizinkan untuk reset password.');
        }

        $tanggalLahir = date('Ymd', strtotime($pegawai->tgl_lahir));

        $pegawai->password = $tanggalLahir;
        $pegawai->save();

        return redirect()->back()->with('success', 'Password berhasil direset ke tanggal lahir.');
    }

    public function dashboardAdmin()
    {
        $pegawai = session('user'); 
        return view('dashboard.admin', compact('pegawai'));
    }

    public function dashboardCS()
    {
        $pegawai = session('user'); 
        return view('dashboard.cs', compact('pegawai'));
    }

    public function dashboardOwner()
    {
        $pegawai = session('user'); 
        return view('dashboard.owner', compact('pegawai'));
    }

    public function dashboardGudang()
    {
        $pegawai = session('user'); 
        return view('dashboard.gudang', compact('pegawai'));
    }


    public function kurirHunter()
    {
        $pegawai = Pegawai::whereIn('jabatan', ['kurir', 'hunter'])->get();
        return response()->json($pegawai);  
    }

}
