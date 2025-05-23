<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function welcome()
    {
        $barangs = Barang::all(); // Ambil semua data barang
        return view('welcome', compact('barangs'));
    }
}
