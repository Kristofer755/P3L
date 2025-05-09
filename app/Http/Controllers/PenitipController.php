<?php

namespace App\Http\Controllers;

use App\Models\penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PenitipController extends Controller
{
    public function index()
    {
        $allPenitip = Penitip::all();
        return response()->json($allPenitip);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_penitip' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/',
            'NIK' => 'required|digits:16|unique:penitip,NIK',
            'no_telp' => 'required|numeric',
            'saldo' => 'required|numeric',
            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        $path = $request->file('foto_ktp')->store('ktp', 'public');

        $penitip = Penitip::create([
            'id_penitip' => $request->id_penitip,
            'nama_penitip' => $validatedData['nama_penitip'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'NIK' => $validatedData['NIK'],
            'no_telp' => $validatedData['no_telp'],
            'saldo' => $validatedData['saldo'],
            'foto_ktp' => $path,
        ]);

        return response()->json([
            'message' => 'Penitip Berhasil Didaftarkan',
            'penitip' => $penitip,
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_penitip' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/',
            'NIK' => 'sometimes|digits:16',
            'no_telp' => 'required|numeric',
            'saldo' => 'required|numeric',
            'foto_ktp' => 'sometimes|image|mimes:jpg,jpeg,png',
            ]);

        $penitip = Penitip::find($id);

        if (!$penitip) {
            return response()->json(['message' => 'Penitip tidak ditemukan atau hanya CS!!!'], 403);
        }

        if ($request->hasFile('foto_ktp')) {
            $path = $request->file('foto_ktp')->store('ktp', 'public');
            $validatedData['foto_ktp'] = $path; 
        }

        $penitip->update($validatedData);
        return response()->json($penitip);
    }

    public function destroy(string $id)
    {
        $penitip = Penitip::find($id);

        if (!$penitip) {
            return response()->json(['message' => 'Penitip tidak ditemukan atau hanya Admin!!!'], 403);
        }

        $penitip->delete();
        return response()->json(['message' => 'Penitip berhasil dihapus.']);
    }

    public function search($nama_penitip)
    {
        $results = Penitip::where('nama_penitip', 'like', '%' . $nama_penitip . '%')->get();

        if ($results->isEmpty()) {
            return response()->json(['message' => 'Tidak ada Penitip yang ditemukan'], 404);
        }

        return response()->json($results, 200);
    }
}
