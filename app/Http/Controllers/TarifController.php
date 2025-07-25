<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TarifController extends Controller
{
    public function index()
    {
        return view('tarif.index', [
            'tarifs'     => Tarif::all()
        ]);
    }

    public function edit($id)
    {
        return view('tarif.edit', [
            'tarif'     => Tarif::find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $tarif = Tarif::find($id);
        $validator = Validator::make($request->all(), [
            'm3'     => 'required',
            'beban'  => 'required',
            'sampah' => 'required',
            'masjid' => 'required',
            'denda'  => 'required'
        ], [
            'm3'     => 'Form wajib diisi !',
            'beban'  => 'Form wajib diisi !',
            'sampah' => 'Form wajib diisi !',
            'masjid' => 'Form wajib diisi !',
            'denda'  => 'Form denda wajib diisi !'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tarif->update([
            'm3'        => $request->m3,
            'beban'     => $request->beban,
            'sampah'    => $request->sampah,
            'masjid'    => $request->masjid,
            'denda'     => $request->denda
        ]);

        return redirect('/tarif')->with('success', 'Berhasil memperbarui tarif');
    }
}