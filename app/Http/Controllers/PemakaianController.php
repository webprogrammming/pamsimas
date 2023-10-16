<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bulan;
use App\Models\Tahun;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Pemakaian;
use Illuminate\Support\Facades\Validator;

class PemakaianController extends Controller
{
    public function index()
    {
        return view('catat-pemakaian.index', [
            'users'     => User::where('role_id', '2')->get(),
            'bulans'    => Bulan::all(),
            'tahuns'    => Tahun::all()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penggunaan_awal'   => 'required',
            'penggunaan_akhir'  => 'required',
            'jumlah_penggunaan' => 'required',
            'user_id'           => 'required',
            'bulan_id'          => 'required',
            'tahun_id'          => 'required',
        ], [
            'penggunaan_awal.required'  => 'Form wajib diisi !',
            'penggunaan_akhir.required' => 'Form wajib diisi !',
            'jumlah_penggunaan.required'=> 'Form wajib diisi !',
            'user_id.required'          => 'Form wajib diisi !',
            'bulan_id.required'         => 'Form wajib diisi !',
            'tahun_id.required'         => 'Form wajib diisi !',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tarif              = Tarif::first();
        $jumlah_penggunaan  = $request->jumlah_penggunaan;
        $m3                 = $tarif->m3;
        $beban              = $tarif->beban;
        $jumlah_pembayaran  = ($jumlah_penggunaan * $m3) + $beban;

        $data = $request->all();
        $data['jumlah_pembayaran']  = $jumlah_pembayaran;

        Pemakaian::create($data);

        return redirect()->back()->with('success', 'Data pemakaian berhasil di simpan !');
    }
}
