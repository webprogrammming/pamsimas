@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white">Data Pemakaian Pelanggan</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_id" class="table display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pelanggan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Jumlah Pemakaian M3</th>
                                    <th>Status</th>
                                    <th>Total Biaya</th>
                                    <th>Periode</th>
                                    <th>Batas Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemakaians as $pemakaian)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pemakaian->user->no_pelanggan }}</td>
                                        <td>{{ $pemakaian->user->name }}</td>
                                        <td>{{ $pemakaian->jumlah_penggunaan}}</td>
                                        <td>
                                            @if ($pemakaian->status == 'belum dibayar')
                                                <span class="badge text-bg-warning p-2">{{ $pemakaian->status }}</span>
                                            @else
                                                <span class="badge text-bg-success p-2">{{ $pemakaian->status }}</span>
                                            @endif
                                        </td>
                                        <td>Rp. {{ $pemakaian->jumlah_pembayaran}}</td>
                                        <td>{{ $pemakaian->periode->periode }}</td>
                                        <td>{{ $pemakaian->batas_bayar }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>    
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready( function () {
            $('#table_id').DataTable();
        });
    </script>
@endsection