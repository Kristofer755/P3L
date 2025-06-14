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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiController as PembeliTransaksiController;
use App\Http\Controllers\DetailTransaksiPembelianController;
use App\Http\Controllers\TransaksiPengirimanController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('home');
});

// Login
Route::get('/login', [LoginSessionController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginSessionController::class, 'login']);
Route::post('/logout', [LoginSessionController::class, 'logout'])->name('logout');
Route::get('/dashboard', [LoginSessionController::class, 'dashboard'])->middleware('auth');

// Produk
Route::get('/produk', [HomeController::class, 'welcome'])->name('produk');
Route::get('/barang/{id}', [BarangController::class, 'show'])->name('barang.detail');
Route::post('/diskusi/store', [DiskusiProdukController::class, 'store'])->name('diskusi.store');

// Register
Route::get('/register', function () {
    return view('auth.register-options');
})->name('register');
Route::get('/register/pembeli', [PembeliController::class, 'showForm'])->name('register.pembeli');
Route::post('/register/pembeli', [PembeliController::class, 'storeWeb'])->name('register.pembeli.store');
Route::get('/register/organisasi', [OrganisasiController::class, 'showForm'])->name('organisasi.form');
Route::post('/register/organisasi', [OrganisasiController::class, 'storeWeb'])->name('organisasi.store');
Route::get('/register/alamat', [AlamatController::class, 'showForm']);
Route::post('/register/alamat', [AlamatController::class, 'storeWeb']);
Route::get('/register/penitip', [PenitipController::class, 'showForm']);
Route::post('/register/penitip', [PenitipController::class, 'storeWeb']);

// Admin
Route::prefix('admin')->group(function () {
    Route::get('/organisasi', [OrganisasiController::class, 'readWeb'])->name('admin.organisasi.index');
    Route::get('/organisasi/search', [OrganisasiController::class, 'searchWeb'])->name('admin.organisasi.search');
    Route::get('/organisasi/edit/{id}', [OrganisasiController::class, 'editWeb'])->name('admin.organisasi.edit');
    Route::put('/organisasi/update/{id}', [OrganisasiController::class, 'updateWeb'])->name('admin.organisasi.update');
    Route::delete('/organisasi/delete/{id}', [OrganisasiController::class, 'deleteWeb'])->name('admin.organisasi.delete');
});

// Pembeli
Route::prefix('pembeli')->group(function () {
    Route::get('/pembeli/pembeli', function () {return view('pembeli.pembeli');})->name('pembeli.pembeli');
    Route::get('/alamat', [AlamatController::class, 'readWeb'])->name('pembeli.alamat');
    Route::get('/alamat/search', [AlamatController::class, 'searchWeb'])->name('pembeli.alamat.search');
    Route::post('/alamat/store', [AlamatController::class, 'storeWeb'])->name('pembeli.alamat.store');
    Route::get('/alamat/edit/{id}', [AlamatController::class, 'editWeb'])->name('pembeli.alamat.edit');
    Route::put('/alamat/update/{id}', [AlamatController::class, 'updateWeb'])->name('pembeli.alamat.update');
    Route::delete('/alamat/delete/{id}', [AlamatController::class, 'deleteWeb'])->name('pembeli.alamat.delete');
});

// CS
Route::prefix('cs')->group(function () {
    Route::get('/penitip', [PenitipController::class, 'readWeb'])->name('cs.penitip.index');
    Route::get('/penitip/search', [PenitipController::class, 'searchWeb'])->name('cs.penitip.search');
    Route::post('/penitip/store', [PenitipController::class, 'storeWeb'])->name('cs.penitip.store');
    Route::get('/penitip/edit/{id}', [PenitipController::class, 'editWeb'])->name('cs.penitip.edit');
    Route::put('/penitip/update/{id}', [PenitipController::class, 'updateWeb'])->name('cs.penitip.update');
    Route::delete('/penitip/delete/{id}', [PenitipController::class, 'deleteWeb'])->name('cs.penitip.delete');
});

// Organisasi
Route::prefix('organisasi')->group(function () {
    Route::get('/request', [RequestDonasiController::class, 'readWeb'])->name('organisasi.request.read');
    Route::get('/request/search', [RequestDonasiController::class, 'searchWeb'])->name('organisasi.request.search');
    Route::post('/request', [RequestDonasiController::class, 'storeWeb'])->name('organisasi.request.store');
    Route::get('/request/{id}/edit', [RequestDonasiController::class, 'editWeb'])->name('organisasi.request.edit');
    Route::put('/request/{id}', [RequestDonasiController::class, 'updateWeb'])->name('organisasi.request.update');
    Route::delete('/request/{id}', [RequestDonasiController::class, 'deleteWeb'])->name('organisasi.request.delete');
});

// Dashboard
Route::get('/dashboard/pembeli', [PembeliController::class, 'showBarang'])->name('dashboard.pembeli');
Route::view('/dashboard/penitip', 'dashboard.penitip')->name('dashboard.penitip');
Route::view('/dashboard/admin', 'dashboard.admin')->name('dashboard.admin');
Route::view('/dashboard/gudang', 'dashboard.gudang')->name('dashboard.gudang');
// Route::view('/dashboard/owner', 'dashboard.owner')->name('dashboard.owner');
Route::view('/dashboard/cs', 'dashboard.cs')->name('dashboard.cs');
Route::view('/dashboard/organisasi', 'dashboard.organisasi')->name('dashboard.organisasi');

// Profil
Route::get('/penitip/profil', [PenitipController::class, 'showProfile'])->name('penitip.profil');
Route::get('/pembeli/profil', [PembeliController::class, 'showProfile'])->name('pembeli.profil');

// Pegawai
Route::get('/pegawai/{id}/reset-password', [PegawaiController::class, 'resetPassword'])->name('pegawai.resetPassword');
Route::get('/dashboard-admin', [PegawaiController::class, 'dashboardAdmin'])->name('dashboard.admin');
Route::get('/dashboard/cs', [PegawaiController::class, 'dashboardCS'])->name('dashboard.cs');
Route::get('/dashboard/owner', [PegawaiController::class, 'dashboardOwner'])->name('dashboard.owner');
Route::get('/dashboard/gudang', [PegawaiController::class, 'dashboardGudang'])->name('dashboard.gudang');

// // Kirim link reset ke email
// Route::post('/pembeli/send-reset-link', [PembeliController::class, 'sendResetLink'])->name('pembeli.sendResetLink');

// // Tampilkan form reset password
// Route::get('/pembeli/reset-password-form', [PembeliController::class, 'showResetForm'])->name('pembeli.resetForm');

// // Proses reset password
// Route::post('/pembeli/reset-password', [PembeliController::class, 'resetPassword'])->name('pembeli.resetPassword');

// // Tampilkan form input email
// Route::get('/pembeli/request-reset', function () {
//     return view('pembeli.request-reset');
// })->name('pembeli.requestReset');

// Diskusi
Route::prefix('diskusi')->group(function () {
    Route::get('/', [DiskusiProdukController::class, 'index'])->name('diskusi.index');
    Route::get('/{id}', [DiskusiProdukController::class, 'show'])->name('diskusi.show');
    Route::post('/store', [DiskusiProdukController::class, 'store'])->name('diskusi.store');
    Route::post('/kirim', [DiskusiProdukController::class, 'kirimPesan'])->name('diskusi.kirim');
});

Route::prefix('pembeli/transaksi') ->middleware('auth.pembeli') ->group(function () {
    Route::get('/',    [PembeliTransaksiController::class, 'index'])->name('pembeli.transaksi.index');
    Route::get('/{id}',[PembeliTransaksiController::class, 'detail'])->name('pembeli.transaksi.detail');
});

// — Beli Sekarang (single‐item checkout) —
Route::get('/beli/{id_barang}', [TransaksiController::class, 'beliSekarang'])->name('pembeli.beli');
Route::post('/transaksi/proses', [TransaksiController::class, 'prosesPembelian'])->name('transaksi.proses');

// — Keranjang —
Route::get    ('/keranjang',           [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post   ('/keranjang/tambah/{id_barang}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::delete ('/keranjang/hapus/{id_barang}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
Route::get    ('/keranjang/checkout', [TransaksiController::class, 'checkoutKeranjang'])->name('keranjang.checkout');
Route::post   ('/transaksi/proses-keranjang', [TransaksiController::class, 'prosesKeranjang'])->name('transaksi.prosesKeranjang');

// Tampilkan form upload bukti
Route::get('/pembeli/transaksi/bukti/{id}', [TransaksiController::class, 'formBukti'])->name('transaksi.bukti');
// Simpan bukti pembayaran
Route::post('/pembeli/transaksi/bukti/{id}', [TransaksiController::class, 'uploadBukti'])->name('transaksi.uploadBukti');

Route::get('/transaksi/batal/{id}', [TransaksiController::class, 'cancelBukti'])->name('transaksi.batal');


Route::get('cs/validasi', [PegawaiController::class, 'CSIndex'])->name('cs.validasi.index');
Route::post('cs/validasi/{id}/approve', [PegawaiController::class, 'approve'])->name('cs.validasi.approve');
Route::post('cs/validasi/{id}/reject',  [PegawaiController::class, 'reject']) ->name('cs.validasi.reject');

Route::post('/update-fcm-token', [PenitipController::class, 'updateFcmToken']);

Route::prefix('gudang/pengiriman')->group(function () {
    Route::get('/', [TransaksiPengirimanController::class, 'index'])->name('gudang.pengiriman.index');
    Route::get('/{id}', [TransaksiPengirimanController::class, 'show'])->name('gudang.pengiriman.show');
    Route::post('/{id}/jadwal', [TransaksiPengirimanController::class, 'jadwalkan'])->name('gudang.pengiriman.jadwal');
    Route::post('/{id}/selesai', [TransaksiPengirimanController::class, 'konfirmasiSelesai'])->name('gudang.pengiriman.selesai');
    Route::get('/{id}/nota', [TransaksiPengirimanController::class, 'cetakNota'])->name('gudang.pengiriman.nota');
});

Route::get('/gudang/pengiriman/{id}/nota', [NotaPengirimanController::class, 'cetakNota']);

// Detail
Route::get('/gudang/pengiriman/{id}', [TransaksiPengirimanController::class, 'show']);

// Simpan Jadwal
Route::post('/gudang/pengiriman/{id}/jadwalkan', [TransaksiPengirimanController::class, 'jadwalkan']);

// Cetak Nota
Route::get('/gudang/pengiriman/{id}/nota', [TransaksiPengirimanController::class, 'cetakNota']);

Route::post('/gudang/pengiriman/assign/{id}', [TransaksiPengirimanController::class, 'assignKurir'])->name('gudang.pengiriman.assign');

Route::get('/gudang/pengiriman', [TransaksiPengirimanController::class, 'index'])->name('gudang.pengiriman.index');

Route::get('/gudang/pengiriman/{id}', [TransaksiPengirimanController::class, 'show'])->name('gudang.pengiriman.show');

Route::get('/gudang/pengiriman/{id}/nota', [TransaksiPengirimanController::class, 'cetakNota'])->name('gudang.pengiriman.nota');

// Laporan
Route::get('/laporan/request_donasi', [LaporanController::class, 'requestDonasiPdf'])->name('laporan.request_donasi');

Route::get('/laporan/donasi', [LaporanController::class, 'laporanDonasiPdf'])->name('laporan.donasi');

Route::get('/laporan/penitip/pdf', [LaporanController::class, 'cetakPdfLaporanPenitip'])->name('laporan.penitip.pdf');

Route::post('/laporan/penitip', [LaporanController::class, 'laporanPenitipPdf'])->name('laporan.penitip');