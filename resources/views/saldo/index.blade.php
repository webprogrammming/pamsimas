@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <h5 class="card-title fw-semibold text-white">Saldo PAMDes</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card overflow-hidden bg-success text-white">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-9 fw-semibold text-white">Saldo Saat Ini</h5>
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="text-white">Rp. {{ number_format($saldo, 2, ',', '.') }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card overflow-hidden bg-primary text-white">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-9 fw-semibold text-white">Saldo Masuk</h5>
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="text-white">Rp. {{ number_format($uangMasuk, 2, ',', '.') }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card overflow-hidden bg-warning text-white">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-9 fw-semibold text-white">Saldo Keluar</h5>
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="text-white">Rp. {{ number_format($uangKeluar, 2, ',', '.') }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="table-responsive">
                            <table id="table_id" class="table display">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($saldoHistories as $history)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $history->created_at->format('d-m-Y') }}</td>
                                            <td>Rp. {{ number_format($history->nominal, 2, ',', '.') }}</td>
                                            <td>
                                                @if ($history->status == 'masuk')
                                                    <span class="badge text-bg-success pb-2">{{ $history->status }}</span>
                                                @else
                                                    <span class="badge text-bg-warning pb-2">{{ $history->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $history->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });
    </script>
@endsection
