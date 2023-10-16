<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pemakaian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('pembayaran.index', [
            'users' => User::where('role_id', '2')->get(),
        ]);
    }

    public function getData($user_id)
    {
        $dataPemakaian = Pemakaian::where('user_id', $user_id)
            ->where('status', 'belum dibayar')
            ->with('bulan')
            ->first();

        return response()->json($dataPemakaian);
    }
}
