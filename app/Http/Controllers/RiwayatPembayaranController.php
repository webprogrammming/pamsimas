<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\pdf as PDF;
use Faker\Provider\ar_EG\Person;

class RiwayatPembayaranController extends Controller
{
    public function index()
    {
        return view('riwayat-pembayaran.index');
    }

    public function getRiwayatPembayaran(Request $request)
    {
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $pembayaran = Pembayaran::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $pembayaran->whereBetween('tgl_bayar', [$tanggalMulai, $tanggalSelesai])
                ->orderBy('id', 'DESC');
        }

        $data = $pembayaran->with('pemakaian.user')->get();

        if (empty($tanggalMulai) && empty($tanggalSelesai)) {
            $data = Pembayaran::with('pemakaian.user')
                ->orderBy('id', 'DESC')
                ->get();
        }

        return response()->json($data);
    }

    public function print(Pembayaran $id)
    {
        $pembayaran = Pembayaran::find($id);
        $tarif      = Tarif::first();

        $logoLombokPath = storage_path('app/public/logo/logo_lombok.png');
        $logoBumdesPath = storage_path('app/public/logo/logo_bumdes.png');
        $logoLombok     = base64_encode(file_get_contents($logoLombokPath));
        $logoBumdes     = base64_encode(file_get_contents($logoBumdesPath));

        $pdf = PDF::loadView('riwayat-pembayaran.print', [
            'pembayaran'    => $pembayaran->first(),
            'tarif'         => $tarif,
            'logoLombok'    => $logoLombok,
            'logoBumdes'    => $logoBumdes
        ]);

        return $pdf->stream('print.pdf');
    }
}