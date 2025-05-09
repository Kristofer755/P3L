<?php

use App\Http\Controllers\AlamatController;
use App\Http\Controllers\OrganisasiController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginSessionController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginSessionController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginSessionController::class, 'login']);
Route::post('/logout', [LoginSessionController::class, 'logout'])->middleware('auth');
Route::get('/dashboard', [LoginSessionController::class, 'dashboard'])->middleware('auth');

Route::get('/register/organisasi', [OrganisasiController::class, 'showForm'])->name('organisasi.form');
Route::post('/register/organisasi', [OrganisasiController::class, 'storeWeb'])->name('organisasi.store');
// Route::get('/admin/organisasi', [OrganisasiController::class, 'readWeb'])->name('admin.dashboard');
// Route::get('/admin/organisasi/search', [OrganisasiController::class, 'searchWeb']);
// Route::post('admin/organisasi/edit/{id}', [OrganisasiController::class, 'updateWeb']);
// Route::post('admin/organisasi/delete/{id}', [OrganisasiController::class, 'deleteWeb']);

Route::prefix('admin/organisasi')->group(function () {
    // Main dashboard view
    Route::get('/', [OrganisasiController::class, 'readWeb'])->name('dashboard.admin');
    
    // Search functionality
    Route::get('/search', [OrganisasiController::class, 'searchWeb'])->name('dashboard.admin.search');
    
    // Edit form 
    Route::get('/edit/{id}', [OrganisasiController::class, 'editWeb'])->name('dashboard.admin.edit');
    
    // Update record
    Route::post('/update/{id}', [OrganisasiController::class, 'updateWeb'])->name('dashboard.admin.update');
    
    // Delete record
    Route::delete('/delete/{id}', [OrganisasiController::class, 'deleteWeb'])->name('dashboard.admin.delete');
});

Route::get('/register/alamat', [AlamatController::class, 'showForm']);
Route::post('/register/alamat', [AlamatController::class, 'storeWeb']);

// Pembeli
Route::get('/dashboard/pembeli', function () {
    return view('dashboard.pembeli');
})->name('dashboard.pembeli');

// Penitip
Route::get('/dashboard/penitip', function () {
    return view('dashboard.penitip');
})->name('dashboard.penitip');

// Admin
Route::get('/dashboard/admin', function () {
    return view('dashboard.admin');
})->name('dashboard.admin');

// Pegawai Gudang
Route::get('/dashboard/gudang', function () {
    return view('dashboard.gudang');
})->name('dashboard.gudang');

// Owner
Route::get('/dashboard/owner', function () {
    return view('dashboard.owner');
})->name('dashboard.owner');

// Customer Service
Route::get('/dashboard/cs', function () {
    return view('dashboard.cs');
})->name('dashboard.cs');

// Organisasi
Route::get('/dashboard/organisasi', function () {
    return view('dashboard.organisasi');
})->name('dashboard.organisasi');
