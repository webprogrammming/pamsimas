<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\SaldoHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SaldoKeluarController extends Controller
{
    public function index()
    {
        return view('saldo-keluar.index', [
            'SaldoKeluars'   => SaldoHistory::where('status', 'keluar')
                ->orderBy('id', 'DESC')
                ->get()
        ]);
    }

    public function create()
    {
        return view('saldo-keluar.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nominal'       => 'required|numeric',
            'keterangan'    => 'required',
        ], [
            'nominal.required'      => 'Form wajib di isi !',
            'nominal.numeric'       => 'Inputan harus berupa angka !',
            'keterangan.required'   => 'Form wajib di isi !'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $saldo  = Saldo::first();
        $status = 'keluar';

        if ($saldo->saldo < $request->nominal) {
            return back()->with('error', 'Saldo tidak mencukupi untuk transaksi ini.');
        }

        $saldo->saldo -= $request->nominal;
        $saldo->save();

        SaldoHistory::create([
            'saldo_id'      => $request->saldo_id,
            'nominal'       => $request->nominal,
            'keterangan'    => $request->keterangan,
            'status'        => $status
        ]);

        return redirect('/saldo-keluar')->with('success', 'Berhasil menambahkan saldo !');
    }
}