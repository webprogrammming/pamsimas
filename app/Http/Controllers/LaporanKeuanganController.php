<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Saldo;
use App\Models\SaldoHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LaporanKeuanganController extends Controller
{
    public function index()
    {
        return view('laporan-keuangan.index');
    }

    public function getLaporanKeuangan(Request $request)
    {
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $history = SaldoHistory::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $history->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])
                ->orderBy('id', 'DESC');
        }

        $data = $history->get();

        if (empty($tanggalMulai) && empty($tanggalSelesai)) {
            $data = SaldoHistory::orderBy('id', 'DESC')->get();
        }

        return response()->json($data);
    }

    public function printLaporanKeuangan(Request $request)
    {
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $history = SaldoHistory::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $history->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])
                ->orderBy('id', 'DESC');
        }

        $data = $history->get();

        if (empty($tanggalMulai) && empty($tanggalSelesai)) {
            $data = SaldoHistory::orderBy('id', 'DESC')->get();
        }
        $saldo = Saldo::first();

        $pdf  = new Dompdf();
        $html = view('laporan-keuangan/print-keuangan', compact('data', 'tanggalMulai', 'tanggalSelesai', 'saldo'));
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        $pdf->stream('print-keuangan.pdf', ['Attachment' => false]);
        exit();
    }
}
