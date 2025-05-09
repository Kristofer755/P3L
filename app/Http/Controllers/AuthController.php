<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $data = $req->validate([
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'type'       => 'required|in:pegawai,pembeli,penjual',
            'profile'    => 'required|array',
        ]);

        // Buat profile dahulu
        $profileData = $data['profile'];
        switch ($data['type']) {
            case 'pegawai':
                $profile = \App\Models\Pegawai::create($profileData);
                break;
            case 'pembeli':
                $profile = \App\Models\Pembeli::create($profileData);
                break;
            case 'penjual':
                $profile = \App\Models\Penjual::create($profileData);
                break;
        }

        // Buat user
        $user = User::create([
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
            'type'       => $data['type'],
            'profile_id' => $profile->id,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user'=>$user,'token'=>$token], 201);
    }

    public function login(Request $req)
    {
        $creds = $req->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($creds)) {
            return response()->json(['error'=>'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $user->load('profile');
        if ($user->type === 'pegawai') {
            $user->load('roles');
        }

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user'=>$user,'token'=>$token], 200);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out'], 200);
    }
}