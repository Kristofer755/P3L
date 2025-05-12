<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginSessionController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DiskusiProdukController;
use App\Http\Controllers\RequestDonasiController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginSessionController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginSessionController::class, 'login']);
Route::post('/logout', [LoginSessionController::class, 'logout'])->middleware('auth');
Route::get('/dashboard', [LoginSessionController::class, 'dashboard'])->middleware('auth');

Route::get('/register/organisasi', [OrganisasiController::class, 'showForm'])->name('organisasi.form');
Route::post('/register/organisasi', [OrganisasiController::class, 'storeWeb'])->name('organisasi.store');
Route::get('/register/pembeli', [PembeliController::class, 'showForm'])->name('register.pembeli.form');
Route::post('/register/pembeli', [PembeliController::class, 'storeWeb'])->name('register.pembeli.store');

Route::prefix('admin')->group(function () {
    Route::get('/organisasi', [OrganisasiController::class, 'readWeb'])->name('admin.organisasi.index');
    Route::get('/organisasi/search', [OrganisasiController::class, 'searchWeb'])->name('admin.organisasi.search');
    Route::get('/organisasi/edit/{id}', [OrganisasiController::class, 'editWeb'])->name('admin.organisasi.edit');
    Route::put('/organisasi/update/{id}', [OrganisasiController::class, 'updateWeb'])->name('admin.organisasi.update');
    Route::delete('/organisasi/delete/{id}', [OrganisasiController::class, 'deleteWeb'])->name('admin.organisasi.delete');
});

Route::prefix('pembeli')->group(function () {
    Route::get('/alamat', [AlamatController::class, 'readWeb'])->name('pembeli.alamat');
    Route::get('/alamat/search', [AlamatController::class, 'searchWeb'])->name('pembeli.alamat.search');
    Route::post('/alamat/store', [AlamatController::class, 'storeWeb'])->name('pembeli.alamat.store');
    Route::get('/alamat/edit/{id}', [AlamatController::class, 'editWeb'])->name('pembeli.alamat.edit');
    Route::put('/alamat/update/{id}', [AlamatController::class, 'updateWeb'])->name('pembeli.alamat.update');
    Route::delete('/alamat/delete/{id}', [AlamatController::class, 'deleteWeb'])->name('pembeli.alamat.delete');
});

Route::prefix('cs')->group(function () {
    Route::get('/penitip', [PenitipController::class, 'readWeb'])->name('cs.penitip.index');
    Route::get('/penitip/search', [PenitipController::class, 'searchWeb'])->name('cs.penitip.search');
    Route::post('/penitip/store', [PenitipController::class, 'storeWeb'])->name('cs.penitip.store');
    Route::get('/penitip/edit/{id}', [PenitipController::class, 'editWeb'])->name('cs.penitip.edit');
    Route::put('/penitip/update/{id}', [PenitipController::class, 'updateWeb'])->name('cs.penitip.update');
    Route::delete('/penitip/delete/{id}', [PenitipController::class, 'deleteWeb'])->name('cs.penitip.delete');
});

Route::prefix('organisasi')->group(function () {
    Route::get('/request', [RequestDonasiController::class, 'readWeb'])->name('organisasi.request.read');
    Route::get('/request/search', [RequestDonasiController::class, 'searchWeb'])->name('organisasi.request.search');
    Route::post('/request', [RequestDonasiController::class, 'storeWeb'])->name('organisasi.request.store');
    Route::get('/request/{id}/edit', [RequestDonasiController::class, 'editWeb'])->name('organisasi.request.edit');
    Route::put('/request/{id}', [RequestDonasiController::class, 'updateWeb'])->name('organisasi.request.update');
    Route::delete('/request/{id}', [RequestDonasiController::class, 'deleteWeb'])->name('organisasi.request.delete');
});


Route::view('/dashboard/pembeli', 'dashboard.pembeli')->name('dashboard.pembeli');
Route::view('/dashboard/penitip', 'dashboard.penitip')->name('dashboard.penitip');
Route::view('/dashboard/admin', 'dashboard.admin')->name('dashboard.admin');
Route::view('/dashboard/gudang', 'dashboard.gudang')->name('dashboard.gudang');
Route::view('/dashboard/owner', 'dashboard.owner')->name('dashboard.owner');
Route::view('/dashboard/cs', 'dashboard.cs')->name('dashboard.cs');
Route::view('/dashboard/organisasi', 'dashboard.organisasi')->name('dashboard.organisasi');

Route::get('/pegawai/{id}/reset-password', [PegawaiController::class, 'resetPassword'])->name('pegawai.resetPassword');
Route::get('/dashboard-admin', [PegawaiController::class, 'dashboardAdmin'])->name('dashboard.admin');
Route::get('/dashboard/cs', [PegawaiController::class, 'dashboardCS'])->name('dashboard.cs');
Route::get('/dashboard/owner', [PegawaiController::class, 'dashboardOwner'])->name('dashboard.owner');
Route::get('/dashboard/gudang', [PegawaiController::class, 'dashboardGudang'])->name('dashboard.gudang');

// Kirim link reset ke email
Route::post('/pembeli/send-reset-link', [PembeliController::class, 'sendResetLink'])->name('pembeli.sendResetLink');

// Tampilkan form reset password
Route::get('/pembeli/reset-password-form', [PembeliController::class, 'showResetForm'])->name('pembeli.resetForm');

// Proses reset password
Route::post('/pembeli/reset-password', [PembeliController::class, 'resetPassword'])->name('pembeli.resetPassword');

// Tampilkan form input email
Route::get('/pembeli/request-reset', function () {
    return view('pembeli.request-reset');
})->name('pembeli.requestReset');

Route::prefix('diskusi')->group(function () {
    Route::get('/diskusi', [DiskusiProdukController::class, 'index'])->name('diskusi.index');
    Route::get('/diskusi/create', [DiskusiProdukController::class, 'create'])->name('diskusi.create');
    Route::post('/diskusi', [DiskusiProdukController::class, 'store'])->name('diskusi.store');
    Route::get('/diskusi/{id_diskusi}', [DiskusiProdukController::class, 'show'])->name('diskusi.show');
    Route::post('/diskusi/kirim', [DiskusiProdukController::class, 'kirimPesan'])->name('diskusi.kirim');
});

Route::get('/diskusi/cs', function () {
    return view('diskusi.diskusiCS');
})->name('pembeli.diskusiCS');

Route::get('/diskusi/pembeli', function () {
    return view('diskusi.diskusiPembeli');
})->name('pembeli.diskusiPembeli');