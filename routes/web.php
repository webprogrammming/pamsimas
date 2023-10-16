<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PembayaranController;

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

Route::get('/', function () {
    return view('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/tahun', TahunController::class);

    Route::get('/tarif', [TarifController::class, 'index']);
    Route::get('/tarif/{id}/edit', [TarifController::class, 'edit']);
    Route::put('/tarif/{id}', [TarifController::class, 'update']);

    Route::resource('/pelanggan', PelangganController::class);

    Route::get('/catat-pemakaian', [PemakaianController::class, 'index']);
    Route::post('/catat-pemakaian', [PemakaianController::class, 'store']);

    Route::get('/pembayaran', [PembayaranController::class, 'index']);
    Route::get('/pembayaran/get-data/{user_id}', [PembayaranController::class, 'getData']);
});

require __DIR__.'/auth.php';
