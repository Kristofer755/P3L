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

    // public function resetPassword($id)
    // {
    //     // Ambil pegawai berdasarkan ID
    //     $pegawai = Pegawai::findOrFail($id);

    //     // Cek role yang diperbolehkan
    //     $allowedRoles = ['Admin', 'Owner', 'Customer Service', 'Pegawai Gudang'];
    //     if (!in_array($pegawai->jabatan, $allowedRoles)) {
    //         return redirect()->back()->with('error', 'Role tidak diizinkan untuk reset password.');
    //     }

    //     // Format tanggal lahir jadi string: YYYYMMDD
    //     $tanggalLahir = date('Ymd', strtotime($pegawai->tgl_lahir));

    //     // Set password baru
    //     $pegawai->password = $tanggalLahir;
    //     $pegawai->save();

    //     return redirect()->back()->with('success', 'Password berhasil direset ke tanggal lahir.');
    // }

    public function resetPassword($id)
{
    $pegawai = Pegawai::findOrFail($id);

    $allowedRoles = ['Admin', 'Owner', 'Customer Service', 'Pegawai Gudang'];
    if (!in_array($pegawai->jabatan, $allowedRoles)) {
        return redirect()->back()->with('error', 'Role tidak diizinkan untuk reset password.');
    }

    $noTelp = preg_replace('/\D/', '', $pegawai->no_telp); 
    $firstSix = substr($noTelp, 0, 6); 

    $pegawai->password = ($firstSix); 
    $pegawai->save();

    return redirect()->back()->with('success', 'Password berhasil direset ke 6 digit awal no. telepon.');
}



    public function dashboardAdmin()
    {
        $pegawai = session('user'); // Ambil dari session
        return view('dashboard.admin', compact('pegawai'));
    }

    public function dashboardCS()
    {
        $pegawai = session('user'); // Ambil dari session
        return view('dashboard.cs', compact('pegawai'));
    }

    public function dashboardOwner()
    {
        $pegawai = session('user'); // Ambil dari session
        return view('dashboard.owner', compact('pegawai'));
    }

    public function dashboardGudang()
    {
        $pegawai = session('user'); // Ambil dari session
        return view('dashboard.gudang', compact('pegawai'));
    }


}
