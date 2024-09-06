@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="card-title fw-semibold text-white">Saldo Masuk</h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="/saldo-masuk/create" type="button" class="btn btn-warning float-end">Tambah
                                Saldo Masuk</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
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
                                    @foreach ($SaldoMasuks as $history)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $history->created_at->format('d-m-Y') }}</td>
                                            <td>Rp. {{ number_format($history->nominal, 2, ',', '.') }}</td>
                                            <td>
                                                @if ($history->status == 'masuk')
                                                    <span class="badge text-bg-success pb-2">{{ $history->status }}</span>
                                                @else
                                                    <span class="badge text-bg-warning">{{ $history->status }}</span>
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
