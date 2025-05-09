<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $allPegawai = Pegawai::all();
        return response()->json($allPegawai);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_role' => 'required|exists:role,id_role',
            'nama_pegawai' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/',
            'no_telp' => 'required|numeric',
            'jabatan' => 'required|string',
            'status_pegawai' => 'required|in:Aktif,Nonaktif,aktif,nonaktif',
        ]);

        $pegawai = Pegawai::create([
            'id_pegawai' => $request->id_pegawai,
            'id_role' => $validatedData['id_role'],
            'nama_pegawai' => $validatedData['nama_pegawai'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'no_telp' => $validatedData['no_telp'],
            'jabatan' => $validatedData['jabatan'],
            'status_pegawai' => $validatedData['status_pegawai'],
        ]);

        return response()->json([
            'message' => 'Pegawai Berhasil Login',
            'pegawai' => $pegawai,
        ], 201);
    }

    
}
