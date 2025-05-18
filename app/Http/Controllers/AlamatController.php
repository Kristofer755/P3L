<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Pembeli; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AlamatController extends Controller
{
    public function index()
    {
        $allAlamat = Alamat::all();
        return response()->json($allAlamat);
    }

    public function showForm()
    {
        $pembeliList = Pembeli::all();
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

        $lastNumber = Alamat::selectRaw('MAX(CAST(SUBSTRING(id_alamat, 4) AS UNSIGNED)) as max_id')->value('max_id');
        $newNumber = $lastNumber ? $lastNumber + 1 : 1;
        $newId = 'ALT' . $newNumber;
        $validatedData['id_alamat'] = $newId;

        Alamat::create($validatedData);

        return redirect()->back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function readWeb()
    {
        $user = Session::get('user');
    
        $user = Session::get('user');
        if (!$user || !isset($user['id_pembeli'])) {
            return redirect('/login')->with('error', 'Harap login terlebih dahulu');
        }

    
        $dataAlamat = Alamat::with('pembeli')
                        ->where('id_pembeli', $user->id_pembeli)
                        ->get();
    
        $pembeliList = Pembeli::where('id_pembeli', $user->id_pembeli)->get();
    
        return view('pembeli.alamat', [
            'dataAlamat' => $dataAlamat,
            'pembeliList' => $pembeliList,
            'editMode' => false
        ]);
    }

    public function searchWeb(Request $request)
    {
        $user = Session::get('user');
    
        $user = Session::get('user');
        if (!$user || !isset($user['id_pembeli'])) {
            return redirect('/login')->with('error', 'Harap login terlebih dahulu');
        }

    
        $search = $request->input('search');
    
        $results = Alamat::with('pembeli')
                    ->where('id_pembeli', $user->id_pembeli)
                    ->where('nama_alamat', 'like', '%' . $search . '%')
                    ->get();
    
        $pembeliList = Pembeli::where('id_pembeli', $user->id_pembeli)->get();
    
        return view('pembeli.alamat', [
            'dataAlamat' => $results,
            'pembeliList' => $pembeliList,
            'editMode' => false
        ]);
    }

    public function editWeb($id)
    {
        $user = Session::get('user');
    
        if (!$user || !isset($user['id_pembeli'])) {
            return redirect('/login')->with('error', 'Harap login terlebih dahulu');
        }
    
        $alamat = Alamat::where('id_alamat', $id)
                    ->where('id_pembeli', $user->id_pembeli)
                    ->firstOrFail();
    
        $dataAlamat = Alamat::with('pembeli')
                        ->where('id_pembeli', $user->id_pembeli)
                        ->get();
    
        $pembeliList = Pembeli::where('id_pembeli', $user->id_pembeli)->get();
    
        return view('pembeli.alamat', [
            'alamat' => $alamat,
            'dataAlamat' => $dataAlamat,
            'pembeliList' => $pembeliList,
            'editMode' => true
        ]);
    }
    

    public function updateWeb(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id_pembeli' => 'required|exists:pembeli,id_pembeli',
            'nama_alamat' => 'required|string',
            'detail_alamat' => 'required|string',
            'tipe_alamat' => 'required|string',
            'status_default' => 'required|in:aktif,nonaktif,Aktif,Nonaktif',
        ]);

        $alamat = Alamat::findOrFail($id);
        $alamat->update($validatedData);

        return redirect()->route('pembeli.alamat')->with('success', 'Data Alamat berhasil diupdate!');
    }

    public function deleteWeb($id)
    {
        $alamat = Alamat::findOrFail($id);
        $alamat->delete();

        return redirect()->route('pembeli.alamat')->with('success', 'Alamat berhasil dihapus!');
    }

}