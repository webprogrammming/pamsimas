<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\SaldoHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SaldoMasukController extends Controller
{
    public function index()
    {
        return view('saldo-masuk.index', [
            'SaldoMasuks'   => SaldoHistory::where('status', 'masuk')
                ->orderBy('id', 'DESC')
                ->get()
        ]);
    }

    public function create()
    {
        return view('saldo-masuk.create');
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
        $status = 'masuk';

        $saldo->saldo += $request->nominal;
        $saldo->save();

        SaldoHistory::create([
            'saldo_id'      => $request->saldo_id,
            'nominal'       => $request->nominal,
            'keterangan'    => $request->keterangan,
            'status'        => $status
        ]);

        return redirect('/saldo-masuk')->with('success', 'Berhasil menambahkan saldo !');
    }
}