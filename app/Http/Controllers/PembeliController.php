<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

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

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pembeli,email',
        ]);

        $pembeli = Pembeli::where('email', $request->email)->first();

        $token = Str::random(60);

        $pembeli->reset_token = $token;
        $pembeli->save();

        $resetLink = url('/pembeli/reset-password-form?token=' . $token);

        Mail::raw("Klik link berikut untuk reset password: $resetLink", function ($message) use ($pembeli) {
            $message->to($pembeli->email)
                    ->subject('Reset Password Pembeli');
        });

        return back()->with('success', 'Link reset password telah dikirim ke email.');
    }

    public function showResetForm(Request $request)
    {
        $token = $request->query('token');

        return view('pembeli.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/|confirmed',
        ]);
    
        $pembeli = Pembeli::where('reset_token', $request->token)->first();
    
        if (!$pembeli) {
            return back()->with('error', 'Token tidak valid.');
        }
    
        $pembeli->password = $request->password;
        $pembeli->reset_token = null;
        $pembeli->save();
    
        User::where('email', $pembeli->email)->update([
            'password' => bcrypt($request->password),
        ]);
    
        return redirect('/login')->with('success', 'Password berhasil direset!');
    }

    public function showProfile()
    {
        $pembeli = session('user'); 
        return view('pembeli.profil', compact('pembeli'));
    }

    public function showBarang()
    {
        $barangs = Barang::all(); 
        return view('dashboard.pembeli', compact('barangs'));
    }

    public function beli($id)
    {
        $barang = Barang::findOrFail($id);
        return view('pembeli.checkout', compact('barang'));
    }

    public function keranjang(Request $request, $id)
    {
        // Validasi login
        if (!session()->has('user')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu.');
        }

        $barang = Barang::findOrFail($id);

        // Simpan ke session atau ke database
        $keranjang = session()->get('keranjang', []);
        $keranjang[$id] = [
            'id_barang' => $barang->id_barang,
            'nama_barang' => $barang->nama_barang,
            'harga_barang' => $barang->harga_barang,
            'gambar_barang' => $barang->gambar_barang,
            'jumlah' => ($keranjang[$id]['jumlah'] ?? 0) + 1
        ];

        session()->put('keranjang', $keranjang);

        return redirect()->back()->with('success', 'Barang ditambahkan ke keranjang.');
    }


}
