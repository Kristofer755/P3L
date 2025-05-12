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

    public function showForm()
    {
        return view('register.penitip');
    }

    public function storeWeb(Request $request)
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

        // Generate ID Penitip otomatis (PEN1, PEN2, ...)
        $lastNumber = Penitip::selectRaw('MAX(CAST(SUBSTRING(id_penitip, 4) AS UNSIGNED)) as max_id')->value('max_id');
        $newNumber = $lastNumber ? $lastNumber + 1 : 1;
        $newId = 'PEN' . $newNumber;
        $validatedData['id_penitip'] = $newId;

        // Simpan foto ktp
        $path = $request->file('foto_ktp')->store('ktp', 'public');
        $validatedData['foto_ktp'] = $path;

        Penitip::create($validatedData);
        return redirect()->back()->with('success', 'Penitip berhasil ditambahkan!');
    }

    public function readWeb()
    {
        $allPenitip = Penitip::all();
        return view('cs.penitip', ['penitip' => $allPenitip]);
        return view('cs.penitip', [
            'penitipList' => $allPenitip,  // untuk tabel daftar
            'penitip' => collect([])       // kosongkan default untuk form tambah
        ]);
    }

    public function searchWeb(Request $request)
    {
        $search = $request->input('search');
        $results = Penitip::where('nama_penitip', 'like', '%' . $search . '%')->get();

        return view('cs.penitip', ['penitip' => $results]);
    }

    public function editWeb($id)
    {
        $penitip = Penitip::findOrFail($id);
        return view('cs.penitipEdit', ['penitip' => $penitip]);
    }

    public function updateWeb(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_penitip' => 'required|string',
            'email' => 'required|email',
            'password' => 'nullable|string|min:8|regex:/[A-Z]/|regex:/[0-9]/',
            'NIK' => 'sometimes|digits:16',
            'no_telp' => 'required|numeric',
            'saldo' => 'required|numeric',
            'foto_ktp' => 'sometimes|image|mimes:jpg,jpeg,png',
        ]);

        $penitip = Penitip::findOrFail($id);

        if ($request->hasFile('foto_ktp')) {
            $path = $request->file('foto_ktp')->store('ktp', 'public');
            $validatedData['foto_ktp'] = $path;
        }

        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($request->password);
        } else {
            unset($validatedData['password']);
        }

        $penitip->update($validatedData);

        return redirect()->route('cs.penitip.index')->with('success', 'Data Penitip berhasil diupdate!');
    }


    public function deleteWeb($id)
    {
        $penitip = Penitip::findOrFail($id);
        $penitip->delete();

        return redirect()->route('cs.penitip.index')->with('success', 'Penitip berhasil dihapus!');
    }

}
