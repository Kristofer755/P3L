<?php

namespace App\Http\Controllers;

use App\Models\RequestDonasi;
use App\Models\Organisasi; 
use Illuminate\Http\Request;

class RequestDonasiController extends Controller
{
    public function index()
    {
        $allRequest = RequestDonasi::all();
        return response()->json($allRequest);
    }

    public function showForm()
    {
        $requestList = RequestDonasi::all(); // Ganti dari Request ke RequestDonasi
        return view('organisasi.request', compact('requestList'));
    }

    public function storeWeb(Request $request)
    {
        $validatedData = $request->validate([
            'id_organisasi' => 'required|exists:organisasi,id_organisasi',
            'deskripsi_request' => 'required|string',
            'status_request' => 'required|in:Diminta,Selesai,Diterima,Ditolak,Dikirim',
        ]);

        // Buat ID request otomatis
        $lastNumber = RequestDonasi::selectRaw('MAX(CAST(SUBSTRING(id_request_donasi, 4) AS UNSIGNED)) as max_id')->value('max_id');
        $newNumber = $lastNumber ? $lastNumber + 1 : 1;
        $newId = 'REQ' . $newNumber;

        // Tambahkan field yang sesuai dengan $fillable
        $validatedData['id_request_donasi'] = $newId;
        $validatedData['tgl_request'] = now()->toDateString();

        RequestDonasi::create($validatedData);

        return redirect()->back()->with('success', 'Donasi berhasil direquest!');
    }

    public function readWeb()
    {
        $dataRequest = RequestDonasi::with('organisasi')->get(); 
        $organisasiList = Organisasi::all();

        return view('organisasi.request', [
            'dataRequest' => $dataRequest,
            'organisasiList' => $organisasiList,
            'editMode' => false
        ]);
    }

    public function searchWeb(Request $request)
    {
        $search = $request->input('search');

        $results = RequestDonasi::with('organisasi')
                ->whereHas('organisasi', function ($query) use ($search) {
                    $query->where('nama_organisasi', 'like', '%' . $search . '%');
                })
                ->get();

        $organisasiList = Organisasi::all();

        return view('organisasi.request', [
            'dataRequest' => $results,
            'organisasiList' => $organisasiList,
            'editMode' => false
        ]);
    }

    public function editWeb($id)
    {
        $requestDonasi = RequestDonasi::findOrFail($id);
        $dataRequest = RequestDonasi::with('organisasi')->get();
        $organisasiList = Organisasi::all();

        return view('organisasi.request', [
            'requestDonasi' => $requestDonasi,
            'dataRequest' => $dataRequest,
            'organisasiList' => $organisasiList,
            'editMode' => true
        ]);
    }

    public function updateWeb(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id_organisasi' => 'required|exists:organisasi,id_organisasi',
            'deskripsi_request' => 'required|string',
            'status_request' => 'required|in:Diminta,Selesai,Diterima,Ditolak,Dikirim',
        ]);

        $requestDonasi = RequestDonasi::findOrFail($id);
        $requestDonasi->update($validatedData);

        return redirect()->route('organisasi.request.read')->with('success', 'Data Request berhasil diupdate!');
    }

    public function deleteWeb($id)
    {
        $requestDonasi = RequestDonasi::findOrFail($id);
        $requestDonasi->delete();

        return redirect()->route('organisasi.request.read')->with('success', 'Request berhasil dihapus!');
    }

}
