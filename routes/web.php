<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginSessionController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\OrganisasiController;

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

Route::prefix('admin')->group(function () {
    Route::get('/organisasi', [OrganisasiController::class, 'readWeb'])->name('admin.organisasi.index');
    Route::get('/organisasi/search', [OrganisasiController::class, 'searchWeb'])->name('admin.organisasi.search');
    Route::get('/organisasi/edit/{id}', [OrganisasiController::class, 'editWeb'])->name('admin.organisasi.edit');
    Route::post('/organisasi/update/{id}', [OrganisasiController::class, 'updateWeb'])->name('admin.organisasi.update');
    Route::delete('/organisasi/delete/{id}', [OrganisasiController::class, 'deleteWeb'])->name('admin.organisasi.delete');
});

Route::prefix('pembeli')->group(function () {
    Route::get('/alamat', [AlamatController::class, 'index'])->name('pembeli.alamat.index');
    Route::post('/alamat/store', [AlamatController::class, 'store'])->name('pembeli.alamat.store');
    Route::get('/alamat/edit/{id}', [AlamatController::class, 'edit'])->name('pembeli.alamat.edit');
    Route::put('/alamat/update/{id}', [AlamatController::class, 'update'])->name('pembeli.alamat.update');
    Route::delete('/alamat/delete/{id}', [AlamatController::class, 'destroy'])->name('pembeli.alamat.delete');
});

Route::get('/register/pembeli', [PembeliController::class, 'showForm'])->name('register.pembeli.form');
Route::post('/register/pembeli', [PembeliController::class, 'storeWeb'])->name('register.pembeli.store');

Route::get('/pembeli/alamat', function () {
    return view('pembeli.alamat');
})->name('pembeli.alamat');

Route::get('/admin/organisasi', function () {
    return view('admin.organisasi');
})->name('admin.organisasi');

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
