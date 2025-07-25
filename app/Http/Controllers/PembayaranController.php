<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Saldo;
use App\Models\Tarif;
use App\Models\Periode;
use App\Models\Pemakaian;
use App\Models\Pembayaran;
use App\Models\SaldoHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\pdf as PDF;


class PembayaranController extends Controller
{
    public function index()
    {
        return view('pembayaran.index', [
            'users'     => User::where('role_id', '3')->get(),
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
                'masjid' => $tarif->masjid,
                'sampah' => $tarif->sampah,
                'denda' => $tarif->denda,
            ]);
        }

        return response()->json(['message' => 'Data tarif tidak ditemukan.']);
    }

    public function paymentProcess(Request $request)
    {
        $m3             = $request->input('m3');
        $beban          = $request->input('beban');
        $kd_pembayaran  = 'INV-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $pemakaian_id   = $request->input('pemakaian_id');
        $tgl_bayar      = $request->input('tgl_bayar');
        $uang_cash      = $request->input('uang_cash');
        $kembalian      = $request->input('kembalian');
        $denda          = $request->input('denda');
        $sampah         = $request->input('sampah');
        $masjid         = $request->input('masjid');
        $subTotal       = $request->input('jumlah_pembayaran');

        if ($uang_cash < $subTotal) {
            return response()->json([
                'message' => 'Uang cash tidak mencukupi untuk pembayaran!',
            ], 400);
        }


        $pembayaran = new Pembayaran();
        $pembayaran->m3              = $m3;
        $pembayaran->beban           = $beban;
        $pembayaran->kd_pembayaran   = $kd_pembayaran;
        $pembayaran->tgl_bayar       = $tgl_bayar;
        $pembayaran->pemakaian_id    = $pemakaian_id;
        $pembayaran->denda           = $denda;
        $pembayaran->masjid          = $masjid;
        $pembayaran->sampah          = $sampah;
        $pembayaran->subTotal        = $subTotal;
        $pembayaran->uang_cash       = $uang_cash;
        $pembayaran->kembalian       = $kembalian;

        $pembayaran->save();

        $pemakaian = Pemakaian::find($pemakaian_id);
        $pemakaian->status = 'lunas';
        $pemakaian->save();

        $saldo = Saldo::first();
        $saldo->saldo += $subTotal;
        $saldo->save();

        $saldoMasuk = new SaldoHistory([
            'saldo_id'      => '1',
            'nominal'       => $subTotal,
            'keterangan'    => 'Pembayaran pelanggan',
            'status'        => 'masuk'
        ]);
        $saldoMasuk->save();

        return response()->json([
            'message'    => 'Tagihan air berhasil dibayar !'
        ], 200);
    }


    public function printBuktiPembayaran(Request $request, $id)
    {
        $pemakaian  = Pemakaian::find($id);
        $pembayaran = Pembayaran::where('pemakaian_id', $pemakaian->id)->get();

        if (!$pemakaian) {
            return abort(404);
        }

        if (empty($pembayaran)) {
            return abort(404);
        }

        $detailPenggunaan   = $request->query('detail_penggunaan');
        $tarifM3            = $request->query('tarif_m3');
        $tarifBeban         = $request->query('tarif_beban');
        $denda              = $request->query('denda');
        $sampah             = $request->query('sampah');
        $masjid             = $request->query('masjid');
        $subTotal           = $request->query('jumlah_pembayaran');

        $logoLombokPath = storage_path('app/public/logo/logo_lombok.png');
        $logoBumdesPath = storage_path('app/public/logo/logo_bumdes.png');
        $logoLombok     = base64_encode(file_get_contents($logoLombokPath));
        $logoBumdes     = base64_encode(file_get_contents($logoBumdesPath));

        $pdf = PDF::loadView('pembayaran.bukti-pembayaran', [
            'pemakaian'         => $pemakaian,
            'pembayaran'        => $pembayaran->first(),
            'detail_penggunaan' => $detailPenggunaan,
            'tarif_m3'          => $tarifM3,
            'tarif_beban'       => $tarifBeban,
            'denda'             => $denda,
            'sampah'            => $sampah,
            'masjid'            => $masjid,
            'subTotal'          => $subTotal,
            'logoLombok'        => $logoLombok,
            'logoBumdes'        => $logoBumdes
        ]);
        return $pdf->stream('bukti-pembayaran.pdf');
    }
}