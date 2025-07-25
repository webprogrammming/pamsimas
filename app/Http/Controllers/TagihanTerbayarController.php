<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Barryvdh\DomPDF\Facade\pdf as PDF;
use App\Models\Pemakaian;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagihanTerbayarController extends Controller
{
    public function index()
    {
        return view('tagihan-terbayar.index');
    }

    public function getRiwayatPembayaran(Request $request)
    {
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $user = auth()->user();

        $pembayaran = Pembayaran::with(['pemakaian.user']);

        if ($tanggalMulai && $tanggalSelesai) {
            $pembayaran->whereBetween('tgl_bayar', [$tanggalMulai, $tanggalSelesai]);
        }

        $data = $pembayaran->whereHas('pemakaian', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('id', 'DESC')->get();

        if (empty($tanggalMulai) && empty($tanggalSelesai)) {
            $data = Pembayaran::with(['pemakaian.user'])
                ->whereHas('pemakaian', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('id', 'DESC')
                ->get();
        }

        return response()->json($data);
    }

    public function print(Pembayaran $id)
    {
        $pembayaran = Pembayaran::with('user')->find($id);
        $tarif      = Tarif::first();

        $logoLombokPath = storage_path('app/public/logo/logo_lombok.png');
        $logoBumdesPath = storage_path('app/public/logo/logo_bumdes.png');
        $logoLombok     = base64_encode(file_get_contents($logoLombokPath));
        $logoBumdes     = base64_encode(file_get_contents($logoBumdesPath));

        $pdf = PDF::loadView('tagihan-terbayar.print', [
            'pembayaran'    => $pembayaran->first(),
            'tarif'         => $tarif,
            'logoLombok'    => $logoLombok,
            'logoBumdes'    => $logoBumdes
        ]);

        return $pdf->stream('print.pdf');
    }
}