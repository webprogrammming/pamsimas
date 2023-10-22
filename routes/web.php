<?php

use App\Http\Controllers\CekTagihanPelangganController;
use App\Http\Controllers\LihatPemakaianController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PemakaianPelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\RiwayatPembayaranController;
use App\Models\Pembayaran;

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
    Route::group(['middleware' => 'CheckRole:admin,pelanggan'], function(){
        Route::get('/', function () {
            return view('/dashboard');
        });
    });

    Route::group(['middleware' => 'CheckRole:admin'], function(){
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
        Route::resource('/periode', PeriodeController::class);
    
        Route::resource('/tahun', TahunController::class);
    
        Route::get('/tarif', [TarifController::class, 'index']);
        Route::get('/tarif/{id}/edit', [TarifController::class, 'edit']);
        Route::put('/tarif/{id}', [TarifController::class, 'update']);
    
        Route::resource('/pelanggan', PelangganController::class);
    
        Route::get('/catat-pemakaian/get-data/{user_id}', [PemakaianController::class, 'getData']);
        Route::get('/catat-pemakaian', [PemakaianController::class, 'index']);
        Route::post('/catat-pemakaian', [PemakaianController::class, 'store']);
    
        Route::get('/lihat-pemakaian', [LihatPemakaianController::class, 'index']);
    
        Route::get('/pembayaran', [PembayaranController::class, 'index']);
        Route::post('/pembayaran', [PembayaranController::class, 'bayar']);
        Route::get('/pembayaran/get-data/{user_id}/{periode_id}', [PembayaranController::class, 'getData']);
        Route::get('/tarif/get-data/{user_id}', [PembayaranController::class, 'getTarifData']);
        Route::get('/pembayaran/bukti-pembayaran/{id}', [PembayaranController::class, 'printBuktiPembayaran']);
    
        Route::get('/riwayat-pembayaran/get-data', [RiwayatPembayaranController::class, 'getRiwayatPembayaran']);
        Route::get('/riwayat-pembayaran', [RiwayatPembayaranController::class, 'index']);
        Route::get('/riwayat-pembayaran/print/{id}', [RiwayatPembayaranController::class, 'print']);
    });

    Route::group(['middleware'  => 'CheckRole:pelanggan'], function(){
        Route::get('/pemakaian-pelanggan', [PemakaianPelangganController::class, 'index']);

        Route::get('/cek-tagihan', [CekTagihanPelangganController::class, 'index']);
        Route::get('/cek-tagihan/{id}', [CekTagihanPelangganController::class, 'detailTagihan']);
        Route::post('/cek-tagihan/bayar', [CekTagihanPelangganController::class, 'bayar']);
        Route::post('/midtrans-callback', [CekTagihanPelangganController::class, 'callback']);
    });

});

require __DIR__.'/auth.php';
