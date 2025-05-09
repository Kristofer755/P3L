<?php

namespace App\Http\Controllers;

use App\Models\RequestDonasi;
use App\Models\Organisasi; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RequestDonasiController extends Controller
{
    public function index()
    {
        $allRequest = RequestDonasi::all();
        return response()->json($allRequest);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_request_donasi' => 'required|string',
            'id_organisasi' => 'required|exists:organisasi,id_organisasi',
            'deskripsi_request' => 'required|string',
            'tgl_request' => 'required|date',
            'status_request' => 'required|in:Diminta,Diterima,Ditolak,Selesai,Dikirim',
        ]);

        $requestDonasi = RequestDonasi::create([
            'id_request_donasi' => $validatedData['id_request_donasi'],
            'id_organisasi' => $validatedData['id_organisasi'],
            'deskripsi_request' => $validatedData['deskripsi_request'],
            'tgl_request' => $validatedData['tgl_request'],
            'status_request' => $validatedData['status_request'],
        ]);

        return response()->json([
            'message' => 'Request Donasi berhasil ditambahkan',
            'request_donasi' => $requestDonasi,
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'deskripsi_request' => 'required|string',
            'tgl_request' => 'required|date',
            'status_request' => 'required|in:Diminta,Diterima,Ditolak,Selesai,Dikirim',
        ]);

        $requestDonasi = RequestDonasi::find($id);
        if (!$requestDonasi) {
            return response()->json(['message' => 'Request Donasi tidak ditemukan'], 404);
        }

        $requestDonasi->update($validatedData);
        return response()->json($requestDonasi);
    }

    public function destroy(string $id)
    {
        $requestDonasi = RequestDonasi::find($id);
        if (!$requestDonasi) {
            return response()->json(['message' => 'Request Donasi tidak ditemukan'], 404);
        }

        $requestDonasi->delete();
        return response()->json(['message' => 'Request Donasi berhasil dihapus']);
    }

    public function search($id)
    {
        $results = RequestDonasi::where('id_request_donasi', 'like', '%' . $id . '%')->get();

        if ($results->isEmpty()) {
            return response()->json(['message' => 'Request donasi tidak ditemukan'], 404);
        }

        return response()->json($results, 200);
    }
}
