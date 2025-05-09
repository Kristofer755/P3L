<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Pembeli; 
use Illuminate\Http\Request;

class AlamatController extends Controller
{
    public function index()
    {
        $allAlamat = Alamat::all();
        return response()->json($allAlamat);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_pembeli' => 'required|exists:pembeli,id_pembeli',
            'nama_alamat' => 'required|string',
            'detail_alamat' => 'required|string',
            'tipe_alamat' => 'required|string',
            'status_default' => 'required|in:aktif,nonaktif,Aktif,Nonaktif',
        ]);

        $alamat = Alamat::create([
            'id_alamat' => $request->id_alamat,
            'id_pembeli' => $validatedData['id_pembeli'],
            'nama_alamat' => $validatedData['nama_alamat'],
            'detail_alamat' => $validatedData['detail_alamat'],
            'status_default' => $validatedData['status_default'],
            'tipe_alamat' => $validatedData['tipe_alamat'],
        ]);

        return response()->json([
            'message' => 'Alamat berhasil ditambahkan',
            'alamat' => $alamat,
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_alamat' => 'required|string',
            'detail_alamat' => 'required|string',
            'tipe_alamat' => 'required|string',
            'status_default' => 'required|in:aktif,nonaktif,Aktif,Nonaktif',
        ]);

        $alamat = Alamat::find($id);
        if (!$alamat) {
            return response()->json(['message' => 'Alamat tidak ditemukan '], 403);
        }

        $alamat->update($validatedData);
        return response()->json($alamat);
    }

    public function destroy(string $id)
    {
        $alamat = Alamat::find($id);
        if (!$alamat) {
            return response()->json(['message' => 'Alamat tidak ditemukan '], 403);
        }

        $alamat->delete();
        return response()->json(['message' => 'Alamat berhasil dihapus.']);
    }

    public function search($nama_alamat)
    {
        $results = Alamat::where('nama_alamat', 'like', '%' . $nama_alamat . '%')->get();

        if ($results->isEmpty()) {
            return response()->json(['message' => 'Alamat tidak ditemukan'], 404);
        }

        return response()->json($results, 200);
    }

    public function showForm()
    {
        $pembeliList = \App\Models\Pembeli::all();
        return view('register.alamat', compact('pembeliList'));
    }

    public function storeWeb(Request $request)
    {
        $validatedData = $request->validate([
            'id_pembeli' => 'required|exists:pembeli,id_pembeli',
            'nama_alamat' => 'required|string',
            'detail_alamat' => 'required|string',
            'tipe_alamat' => 'required|string',
            'status_default' => 'required|in:aktif,nonaktif,Aktif,Nonaktif',
        ]);

        $lastNumber = \App\Models\Alamat::selectRaw('MAX(CAST(SUBSTRING(id_alamat, 4) AS UNSIGNED)) as max_id')->value('max_id');

        $newNumber = $lastNumber ? $lastNumber + 1 : 1;
        $newId = 'ALT' . $newNumber;

        $validatedData['id_alamat'] = $newId;

        \App\Models\Alamat::create($validatedData);

        return redirect()->back()->with('success', 'Alamat berhasil ditambahkan!');
    }
}