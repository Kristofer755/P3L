<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\DiskusiProdukController;
use App\Http\Controllers\RequestDonasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginSessionController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\MobileLoginController;
use App\Http\Controllers\API\NotifikasiPenitipController;
use App\Http\Controllers\API\NotifikasiPembeliController;
use App\Http\Controllers\API\PengirimanController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

    Route::get('/alamat', [AlamatController::class, 'index']);
    Route::post('/alamat/create', [AlamatController::class, 'store']);
    Route::put('/alamat/update/{id}', [AlamatController::class, 'update']);
    Route::get('/alamat/search/{nama_alamat}', [AlamatController::class, 'search']);
    Route::delete('/alamat/delete/{id}', [AlamatController::class, 'destroy']);

    Route::get('/organisasi', [ OrganisasiController::class, 'index']);
    Route::post('/organisasi/create', [OrganisasiController::class, 'store']);
    Route::put('/organisasi/update/{id}', [OrganisasiController::class, 'update']);
    Route::get('/organisasi/search/{nama_organisasi}', [OrganisasiController::class, 'search']);
    Route::delete('/organisasi/delete/{id}', [OrganisasiController::class, 'destroy']);

    Route::get('/pembeli', [PembeliController::class, 'index']);
    Route::post('/pembeli/create', [PembeliController::class, 'store']);

    Route::get('/diskusi', [DiskusiProdukController::class, 'index']);
    Route::post('/diskusi/create', [DiskusiProdukController::class, 'store']);

    Route::get('/request', [ RequestDonasiController::class, 'index']);
    Route::post('/request/create', [RequestDonasiController::class, 'store']);
    Route::put('/request/update/{id}', [RequestDonasiController::class, 'update']);
    Route::get('/request/search/{id}', [RequestDonasiController::class, 'search']);
    Route::delete('/request/delete/{id}', [RequestDonasiController::class, 'destroy']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/logout', [AuthController::class, 'logout']);
    });


    Route::get('/pegawai', [PegawaiController::class, 'index']);
    Route::post('/pegawai/create', [PegawaiController::class, 'store']);

    Route::get('/penitip', [ PenitipController::class, 'index']);
    Route::post('/penitip/create', [PenitipController::class, 'store']);
    Route::put('/penitip/update/{id}', [PenitipController::class, 'update']);
    Route::get('/penitip/search/{nama_penitip}', [PenitipController::class, 'search']);
    Route::delete('/penitip/delete/{id}', [PenitipController::class, 'destroy']);

    Route::post('/login', [MobileLoginController::class, 'login']);
    Route::get('/pegawai/kurir-hunter', [PegawaiController::class, 'kurirHunter']);

Route::get('/notif-penitip/{id_penitip}', [NotifikasiPenitipController::class, 'getPenitipNotif']);
Route::post('/notif-penitip/read/{id}', [NotifikasiPenitipController::class, 'markAsRead']);

Route::get('/notif-pembeli/{id_pembeli}', [NotifikasiPembeliController::class, 'getPembeliNotif']);
Route::post('/notif-pembeli/read/{id}', [NotifikasiPembeliController::class, 'markAsRead']);

Route::get('pengiriman', [PengirimanController::class, 'index']);
// PATCH /api/pengiriman/{id}
Route::patch('pengiriman/{id}', [App\Http\Controllers\API\PengirimanController::class, 'update']);
