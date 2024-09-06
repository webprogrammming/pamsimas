<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\SaldoHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SaldoController extends Controller
{
    public function index()
    {
        return view('saldo.index', [
            'saldo'             => Saldo::sum('saldo'),
            'uangMasuk'         => SaldoHistory::where('status', 'masuk')->sum('nominal'),
            'uangKeluar'        => SaldoHistory::where('status', 'keluar')->sum('nominal'),
            'saldoHistories'    => SaldoHistory::orderBy('id', 'DESC')->get()
        ]);
    }
}