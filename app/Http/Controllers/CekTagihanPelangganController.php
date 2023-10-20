<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Tarif;
use App\Models\Pemakaian;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CekTagihanPelangganController extends Controller
{
    public function index()
    {
        $user = auth()->user()->id;
        return view('cek-tagihan.index', [
            'tagihans'  => Pemakaian::where('user_id', $user)
                            ->where('status', 'belum dibayar')
                            ->orderBy('id', 'DESC')
                            ->get()
        ]);
    }

    public function detailTagihan($id)
    {
        $tagihan = Pemakaian::find($id);

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $tagihan->id,
                'gross_amount' => $tagihan->jumlah_pembayaran,
            ),
            'customer_details' => array(
                'first_name' => auth()->user()->name,
                'phone' => auth()->user()->no_hp,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        return view('cek-tagihan.detail', [
            'tagihan'   => $tagihan,
            'tarif'     => Tarif::first(),
            'snapToken' => $snapToken
        ]);

    }

    // public function bayar(Request $request, $id)
    // {
    //     $kd_pembayaran  = 'INV-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    //     $pemakaian_id   = $request->input('pemakaian_id');
    //     $tgl_bayar      = $request->input('tgl_bayar');
    //     $denda          = $request->input('denda');
    //     $subTotal       = $request->input('jumlah_pembayaran');
    //     $uang_cash      = '100000';
    //     $kembalian      = '5000';

    //     $pembayaran     = new Pembayaran();
    //     $pembayaran->kd_pembayaran   = $kd_pembayaran;
    //     $pembayaran->tgl_bayar       = $tgl_bayar;
    //     $pembayaran->pemakaian_id    = $pemakaian_id;  
    //     $pembayaran->denda           = $denda;
    //     $pembayaran->subTotal        = $subTotal;
    //     $pembayaran->uang_cash       = $uang_cash;
    //     $pembayaran->kembalian       = $kembalian;

     
    // }
}
