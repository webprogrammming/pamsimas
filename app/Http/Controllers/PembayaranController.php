<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tarif;
use App\Models\Periode;
use App\Models\Pemakaian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('pembayaran.index', [
            'users'     => User::where('role_id', '2')->get(),
            'periodes'  => Periode::where('status', 'Aktif')->get()
        ]);
    }

    public function getData($user_id, $periode_id)
    {
        $dataPemakaian = Pemakaian::where('user_id', $user_id)
            ->where('status', 'belum dibayar')
            ->where('periode_id', $periode_id)
            ->with('bulan')
            ->first();
            
    
        return response()->json($dataPemakaian);
    }

    public function getTarifData()
    {
        $tarif = Tarif::first();

        if ($tarif) {
            return response()->json([
                'm3'    => $tarif->m3,
                'beban' => $tarif->beban,
                'denda' => $tarif->denda,
            ]);
        }

        return response()->json(['message' => 'Data tarif tidak ditemukan.']);
    }

    public function bayar(Request $request)
    {
        $kd_pembayaran  = 'INV-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $pemakaian_id   = $request->input('pemakaian_id');
        $tgl_bayar      = $request->input('tgl_bayar');
        $uang_cash      = $request->input('uang_cash');
        $kembalian      = $request->input('kembalian');

        $pembayaran = new Pembayaran();
        $pembayaran->kd_pembayaran   = $kd_pembayaran;
        $pembayaran->tgl_bayar       = $tgl_bayar;
        $pembayaran->pemakaian_id    = $pemakaian_id;    
        $pembayaran->uang_cash       = $uang_cash;
        $pembayaran->kembalian       = $kembalian;
        $pembayaran->save();

        $pemakaian = Pemakaian::find($pemakaian_id);
        $pemakaian->status = 'lunas';
        $pemakaian->save();

        return response()->json([
           'message'    => 'Tagihan air berhasil dibayar !' 
        ], 200);
    }
}
