<?php

use App\Models\Pembayaran;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SaldoMasukController;
use App\Http\Controllers\SaldoKeluarController;
use App\Http\Controllers\LihatPemakaianController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\TagihanTerbayarController;
use App\Http\Controllers\LaporanPembayaranController;
use App\Http\Controllers\RiwayatPembayaranController;
use App\Http\Controllers\PemakaianPelangganController;
use App\Http\Controllers\CekTagihanPelangganController;
use App\Http\Controllers\SettingsMidtransController;
use App\Models\SettingsMidtrans;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::group(['middleware' => 'CheckRole:admin,petugas,pelanggan'], function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::group(['middleware'  => 'CheckRole:petugas,admin'], function () {
        Route::get('/lihat-pemakaian', [LihatPemakaianController::class, 'index']);
    });

    Route::group(['middleware'  => 'CheckRole:petugas'], function () {
        Route::get('/catat-pemakaian/get-data/{user_id}', [PemakaianController::class, 'getData']);
        Route::get('/catat-pemakaian', [PemakaianController::class, 'index']);
        Route::post('/catat-pemakaian', [PemakaianController::class, 'store']);
        Route::get('/catat-pemakaian/get-data-pelanggan', [PemakaianController::class, 'getDataPelanggan']);
    });

    Route::group(['middleware' => 'CheckRole:admin'], function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/tarif', [TarifController::class, 'index']);
        Route::get('/tarif/{id}/edit', [TarifController::class, 'edit']);
        Route::put('/tarif/{id}', [TarifController::class, 'update']);

        Route::get('/pelanggan/kartu-pelanggan/{id}', [PelangganController::class, 'print']);
        Route::resource('/pelanggan', PelangganController::class);

        Route::resource('/periode', PeriodeController::class);
        Route::resource('/tahun', TahunController::class);

        Route::get('/pembayaran', [PembayaranController::class, 'index']);
        Route::post('/pembayaran', [PembayaranController::class, 'paymentProcess']);
        Route::post('/pembayaran/get-data/{user_id}/{periode_id}', [PembayaranController::class, 'getData']);
        Route::get('/tarif/get-data/{user_id}', [PembayaranController::class, 'getTarifData']);
        Route::get('/pembayaran/bukti-pembayaran/{id}', [PembayaranController::class, 'printBuktiPembayaran']);

        Route::get('/riwayat-pembayaran/get-data', [RiwayatPembayaranController::class, 'getRiwayatPembayaran']);
        Route::get('/riwayat-pembayaran', [RiwayatPembayaranController::class, 'index']);
        Route::get('/riwayat-pembayaran/print/{id}', [RiwayatPembayaranController::class, 'print']);

        Route::get('/laporan-pembayaran/get-data', [LaporanPembayaranController::class, 'getLaporanPembayaran']);
        Route::get('/laporan-pembayaran', [LaporanPembayaranController::class, 'index']);
        Route::get('/laporan-pembayaran/print-pembayaran', [LaporanPembayaranController::class, 'printLaporanPembayaran']);

        Route::get('/saldo', [SaldoController::class, 'index']);

        Route::get('/saldo-masuk', [SaldoMasukController::class, 'index']);
        Route::get('/saldo-masuk/create', [SaldoMasukController::class, 'create']);
        Route::post('/saldo-masuk', [SaldoMasukController::class, 'store']);

        Route::get('/saldo-keluar', [SaldoKeluarController::class, 'index']);
        Route::get('/saldo-keluar/create', [SaldoKeluarController::class, 'create']);
        Route::post('/saldo-keluar', [SaldoKeluarController::class, 'store']);

        Route::get('/laporan-keuangan/get-data', [LaporanKeuanganController::class, 'getLaporanKeuangan']);
        Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index']);
        Route::get('/laporan-keuangan/print-keuangan', [LaporanKeuanganController::class, 'printLaporanKeuangan']);
    });

    Route::group(['middleware'  => 'CheckRole:pelanggan'], function () {
        Route::get('/pemakaian-pelanggan', [PemakaianPelangganController::class, 'index']);

        Route::get('/cek-tagihan', [CekTagihanPelangganController::class, 'index']);
        Route::get('/cek-tagihan/{id}', [CekTagihanPelangganController::class, 'detailTagihan']);
        Route::post('/cek-tagihan/bayar', [CekTagihanPelangganController::class, 'paymentProcess']);

        Route::get('/tagihan-terbayar/get-data', [TagihanTerbayarController::class, 'getRiwayatPembayaran']);
        Route::get('/tagihan-terbayar', [TagihanTerbayarController::class, 'index']);
        Route::get('/tagihan-terbayar/print/{id}', [TagihanTerbayarController::class, 'print']);
    });
});

require __DIR__ . '/auth.php';
