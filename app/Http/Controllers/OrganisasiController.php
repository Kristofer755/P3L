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

    // public function searchWeb(Request $request)
    // {
    //     $search = $request->input('search');

    //     $results = Organisasi::where('no_telp_organisasi', 'like', '%' . $search . '%')->get();

    //     return view('admin.organisasi', ['organisasi' => $results]);
    // }

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
