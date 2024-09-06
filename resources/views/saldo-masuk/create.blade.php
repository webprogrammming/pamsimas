@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white">Tambah Pemasukan Saldo</h5>
                        </div>
                        <div class="col-6 text-right">
                            <a href="/saldo-masuk" type="button" class="btn btn-warning float-end">Kembali</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="/saldo-masuk">
                        @csrf

                        <input type="hidden" id="1" value="1" name="saldo_id">
                        <label for="nominal" class="form-label">Nominal Masuk <span style="color: red">*</span></label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input type="number" class="form-control" name="nominal">
                        </div>
                        @error('nominal')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan <span style="color: red">*</span></label>
                            <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="10"></textarea>
                            @error('keterangan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary m-1 float-end">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
