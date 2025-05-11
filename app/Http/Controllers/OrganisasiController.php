<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;

class OrganisasiController extends Controller
{
    public function index()
    {
        $allOrganisasi = Organisasi::all();
        return response()->json($allOrganisasi);
    }

    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'nama_organisasi' => 'required|string',
    //         'no_telp_organisasi' => 'required|numeric',
    //         'alamat_organisasi' => 'required|string',
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/',
    //         'status_organisasi' => 'required|in:aktif,nonaktif,Aktif,Nonaktif',
    //     ]);

    //     $lastOrganisasi = Organisasi::orderBy('id_organisasi', 'desc')->first();

    //     if ($lastOrganisasi) {
    //         $lastNumber = (int) str_replace('ORG', '', $lastOrganisasi->id_organisasi);
    //         $newNumber = $lastNumber + 1;
    //     } else {
    //         $newNumber = 1;
    //     }

    //     $newId = 'ORG' . $newNumber;

    //     $organisasi = Organisasi::create([
    //         'id_organisasi' => $newId,
    //         'nama_organisasi' => $validatedData['nama_organisasi'],
    //         'no_telp_organisasi' => $validatedData['no_telp_organisasi'],
    //         'alamat_organisasi' => $validatedData['alamat_organisasi'],
    //         'email' => $validatedData['email'],
    //         'password' => $validatedData['password'],
    //         'status_organisasi' => $validatedData['status_organisasi'],
    //     ]);

    //     return response()->json([
    //         'message' => 'Organisasi berhasil ditambahkan',
    //         'organisasi' => $organisasi,
    //     ], 201);
    // }

    // public function update(Request $request, string $id)
    // {
    //     $validatedData = $request->validate([
    //         'nama_organisasi' => 'required|string',
    //         'no_telp_organisasi' => 'required|numeric',
    //         'alamat_organisasi' => 'required|string',
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/',
    //         'status_organisasi' => 'required|in:aktif,nonaktif,Aktif,Nonaktif',
    //     ]);

    //     $organisasi = Organisasi::find($id);

    //     if (!$organisasi) {
    //         return response()->json(['message' => 'Organisasi tidak ditemukan atau hanya Admin!!!'], 403);
    //     }

    //     $organisasi->update($validatedData);
    //     return response()->json($organisasi);
    // }

    // public function destroy(string $id)
    // {
    //     $organisasi = Organisasi::find($id);

    //     if (!$organisasi) {
    //         return response()->json(['message' => 'Organisasi tidak ditemukan atau hanya Admin!!!'], 403);
    //     }

    //     $organisasi->delete();
    //     return response()->json(['message' => 'Organisasi berhasil dihapus.']);
    // }

    // public function search($nama_organisasi)
    // {
    //     $results = Organisasi::where('nama_organisasi', 'like', '%' . $nama_organisasi . '%')->get();

    //     if ($results->isEmpty()) {
    //         return response()->json(['message' => 'Tidak ada Organisasi yang ditemukan'], 404);
    //     }

    //     return response()->json($results, 200);
    // }

    public function showForm()
    {
        return view('register.organisasi');
    }

    public function storeWeb(Request $request)
    {
        $validatedData = $request->validate([
            'nama_organisasi' => 'required|string',
            'no_telp_organisasi' => 'required|numeric',
            'alamat_organisasi' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/',
            'status_organisasi' => 'required|in:aktif,nonaktif,Aktif,Nonaktif',
        ]);

        $lastNumber = Organisasi::selectRaw('MAX(CAST(SUBSTRING(id_organisasi, 4) AS UNSIGNED)) as max_id')->value('max_id');
        $newNumber = $lastNumber ? $lastNumber + 1 : 1;
        $newId = 'ORG' . $newNumber;
        $validatedData['id_organisasi'] = $newId;
        Organisasi::create($validatedData);
        return redirect()->back()->with('success', 'Organisasi berhasil ditambahkan!');
    }

    public function readWeb()
    {
        $allOrganisasi = Organisasi::all();
        return view('admin.organisasi', ['organisasi' => $allOrganisasi]);
    }

    public function searchWeb(Request $request)
    {
        $search = $request->input('search');

        $results = Organisasi::where('nama_organisasi', 'like', '%' . $search . '%')->get();

        return view('admin.organisasi', ['organisasi' => $results]);
    }

    public function editWeb($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        return view('admin.organisasiEdit', ['organisasi' => $organisasi]);
    }

    public function updateWeb(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_organisasi' => 'required|string',
            'no_telp_organisasi' => 'required|numeric',
            'alamat_organisasi' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/',
            'status_organisasi' => 'required|in:aktif,nonaktif,Aktif,Nonaktif',
        ]);

        $organisasi = Organisasi::findOrFail($id);
        $organisasi->update($validatedData);

        return redirect()->route('admin.organisasi.index')->with('success', 'Data Organisasi berhasil diupdate!');
    }

    public function deleteWeb($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        $organisasi->delete();

        return redirect()->route('admin.organisasi.index')->with('success', 'Organisasi berhasil dihapus!');
    }
}
