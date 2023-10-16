@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white">Pembayaran</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="/pembayaran">
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
                                    <label for="tgl_bayar">Tanggal Hari Ini</label>
                                    <input type="text" class="form-control" name="tgl_bayar" id="tgl_bayar" disabled>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="bulan_id" class="form-label">Periode Bulan</label>
                                            <input type="number" class="form-control" name="bulan_id" id="bulan_id" disabled>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="penggunaan_awal" class="form-label">Penggunaan Awal</label>
                                            <input type="number" class="form-control" name="penggunaan_awal" id="penggunaan_awal" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="penggunaan_akhir" class="form-label">Penggunaan Akhir</label>
                                            <input type="number" class="form-control" name="penggunaan_akhir" id="penggunaan_akhir" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="jumlah_penggunaan" class="form-label">Total Penggunaan</label>
                                            <input type="number" class="form-control" name="jumlah_penggunaan" id="jumlah_penggunaan" disabled> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body bg-warning">
                                        <h3><b>Sub-Total : Rp. <span id="jumlah_pembayaran"></span></b></h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="uang_cash" class="form-label">Masukan uang Pelanggan</label>
                                        <input type="number" class="form-control" name="uang_cash" id="uang_cash">
                                    </div>
                                    <div class="mb-3">
                                        <label for="kembalian" class="form-label">Kembalian</label>
                                        <input type="number" class="form-control" name="kembalian" id="kembalian" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary m-1 float-end">Simpan</button>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Tanggal Hari Ini -->
    <script>
        var today   = new Date();
        var year    = today.getFullYear();
        var month   = (today.getMonth() + 1).toString().padStart(2, '0');
        var day     = today.getDate().toString().padStart(2, '0');

        var formattedDate = year + '-' + month + '-' + day;
        document.getElementById('tgl_bayar').value = formattedDate;
    </script>
    

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();

            $('.js-example-basic-single').change(function(){
                var user_id = $(this).val();

                $.ajax({
                    url: '/pembayaran/get-data/' + user_id,
                    type: 'GET',
                    success: function(data){
                        $('penggunaan_awal').val(data.penggunaan_awal);
                        $('penggunaan_akhir').val(data.penggunaan_akhir);
                        $('jumlah_penggunaan').val(data.jumlah_penggunaan);
                        $('jumlah_pembayaran').val(data.jumlah_pembayaran);
                        $('bulan_id').val(data.bulan);

                        var penggunaan_awal     = parseFloat(data.penggunaan_awal) || 0;
                        var penggunaan_akhir    = parseFloat(data.penggunaan_akhir) || 0;
                        var jumlah_penggunaan   = parseFloat(data.jumlah_penggunaan) || 0;
                        var jumlah_pembayaran   = parseFloat(data.jumlah_pembayaran) || 'LUNAS !';
                        var bulan               = parseFloat(data.bulan_id);

                        $('#penggunaan_awal').val(penggunaan_awal);
                        $('#penggunaan_akhir').val(penggunaan_akhir);
                        $('#jumlah_penggunaan').val(jumlah_penggunaan);
                        $('#jumlah_pembayaran').text(jumlah_pembayaran);
                        $('#bulan_id').val(bulan);
                    }
                });
            });
        });
    </script>

@endsection