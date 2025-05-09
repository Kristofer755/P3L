<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pembeli;
use Illuminate\Http\Request;

class PembeliController extends Controller
{
    public function index()
    {
        $allPembeli = Pembeli::all();
        return response()->json($allPembeli);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pembeli' => 'required|string',
            'no_telp' => 'required|numeric',
            'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/',
            'email' => 'required|email',
            'poin' => 'required|numeric',
        ]);

        $pembeli = Pembeli::create([
            'id_pembeli' => $request->id_pembeli,
            'nama_pembeli' => $validatedData['nama_pembeli'],
            'no_telp' => $validatedData['no_telp'],
            'password' => $validatedData['password'],
            'email' => $validatedData['email'],
            'poin' => $validatedData['poin'],
        ]);

        $user = User::create([
            'name' => $validatedData['nama_pembeli'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Pembeli Berhasil DIdaftarkan',
            'pembeli' => $pembeli,
            'token' => $token,
        ], 201);
    }

    public function storeWeb(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pembeli' => 'required|string',
            'no_telp' => 'required|numeric',
            'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/',
            'email' => 'required|email',
        ]);

        $lastNumber = Pembeli::selectRaw('MAX(CAST(SUBSTRING(id_pembeli, 4) AS UNSIGNED)) as max_id')->value('max_id');
        
        $newNumber = $lastNumber ? $lastNumber + 1 : 1;

        $newId = 'PEM' . $newNumber;

        $validatedData['id_pembeli'] = $newId;

        Pembeli::create($validatedData);

        return redirect()->back()->with('success', 'Pembeli berhasil ditambahkan!');
    }

    public function showForm()
    {
        return view('register.pembeli');
    }
}
