@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white">Catat Pemakaian</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="/catat-pemakaian">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Pilih Nama Pelanggan</label>
                                    <select class="js-example-basic-single" name="user_id" style="width: 100%;">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->no_pelanggan }} | {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="penggunaan_awal" class="form-label">Penggunaan Awal</label>
                                    <input type="number" class="form-control" name="penggunaan_awal" id="penggunaan_awal">
                                </div>
                                <div class="mb-3">
                                    <label for="penggunaan_akhir" class="form-label">Penggunaan Akhir</label>
                                    <input type="number" class="form-control" name="penggunaan_akhir" id="penggunaan_akhir">
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah_penggunaan" class="form-label">Jumlah Penggunaan</label>
                                    <input type="number" class="form-control" name="jumlah_penggunaan" id="jumlah_penggunaan" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="periode_id" class="form-label">Periode Pemakaian</label>
                                    <select class="form-select" name="periode_id" aria-label="Default select example">
                                        @foreach ($periodes as $periode)
                                            <option value="{{ $periode->id }}">{{ $periode->periode }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="batas_bayar" class="form-label">Tanggal Batas Pembayaran</label>
                                    <input type="date" class="form-control" name="batas_bayar" id="batas_bayar">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary m-1 float-end">Simpan</button>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>

    <script>
        const penggunaanAwal      = document.getElementById('penggunaan_awal');
        const penggunaanAkhir     = document.getElementById('penggunaan_akhir');
        const jumlahPenggunaan    = document.getElementById('jumlah_penggunaan');

        penggunaanAwal.addEventListener('input', hitungJumlahPenggunaan);
        penggunaanAkhir.addEventListener('input', hitungJumlahPenggunaan);

        function hitungJumlahPenggunaan(){
            const awal  = parseFloat(penggunaanAwal.value) || 0;
            const akhir = parseFloat(penggunaanAkhir.value) || 0;
            const hasil = akhir - awal;

            jumlahPenggunaan.value = hasil;
        }
    </script>
@endsection