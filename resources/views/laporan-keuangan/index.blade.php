@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white">Laporan Keuangan</h5>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0)" id="print-laporan-keuangan" target="_blank"
                                class="btn btn-warning float-end">Print PDF</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <form id="filter_form" action="/riwayat-pembayaran/get-data" method="GET">
                            <div class="row">
                                <div class="col-md-5 my-2">
                                    <label class="form-label">Pilih Tanggal Mulai :</label>
                                    <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai">
                                </div>
                                <div class="col-md-5 my-2">
                                    <label class="form-label">Pilih Tanggal Selesai :</label>
                                    <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai">
                                </div>
                                <div class="col-md-2 d-flex align-items-end my-2">
                                    <button type="submit" class="btn btn-primary mx-2">Filter</button>
                                    <button type="button" class="btn btn-danger" id="refresh_btn">Refresh</button>
                                </div>
                            </div>
                        </form>
                    </div>

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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Datatables Jquery -->
    <script>
        $(document).ready(function() {
            let table = $('#table_id').DataTable();

            loadData();

            $('#filter_form').submit(function(event) {
                event.preventDefault();
                loadData();
            });

            $('#refresh_btn').on('click', function() {
                refreshTable();
            });


            function loadData() {
                var tanggalMulai = $('#tanggal_mulai').val();
                var tanggalSelesai = $('#tanggal_selesai').val();

                $.ajax({
                    url: '/laporan-keuangan/get-data',
                    type: "GET",
                    dataType: 'JSON',
                    data: {
                        tanggal_mulai: tanggalMulai,
                        tanggal_selesai: tanggalSelesai
                    },
                    success: function(response) {
                        let counter = 1;
                        table.clear().draw();

                        if (response.length === 0) {
                            $('#table_id tbody');
                        } else {
                            $.each(response, function(key, value) {
                                let nominal = parseFloat(value.nominal).toLocaleString(
                                    'id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    });
                                var rawDate = value.created_at;
                                var formattedDate = new Date(rawDate).toLocaleDateString(
                                    'id-ID', {
                                        day: 'numeric',
                                        month: 'numeric',
                                        year: 'numeric'
                                    });

                                let badgeClass = '';
                                if (value.status === 'masuk') {
                                    badgeClass = 'badge text-bg-success pb-2';
                                } else if (value.status === 'keluar') {
                                    badgeClass = 'badge text-bg-warning pb-2';
                                }

                                let saldo = `
                                    <tr class="barang-row" id="index_${value.id}">
                                        <td>${counter++}</td>
                                        <td>${formattedDate}</td>
                                        <td>${nominal}</td>
                                        <td><span class="badge ${badgeClass}">${value.status}</span></td>
                                        <td>${value.keterangan}</td>
                                    </tr>
                                `;
                                table.row.add($(saldo)).draw(false);
                            });

                        }
                    }
                });

            }

            function refreshTable() {
                $('#filter_form')[0].reset();
                loadData();
            }

            $('#print-laporan-keuangan').on('click', function() {
                var tanggalMulai = $('#tanggal_mulai').val();
                var tanggalSelesai = $('#tanggal_selesai').val();

                var url = '/laporan-keuangan/print-keuangan';

                if (tanggalMulai && tanggalSelesai) {
                    url += '?tanggal_mulai=' + tanggalMulai + '&tanggal_selesai=' + tanggalSelesai;
                }

                window.location.href = url;
            });

        });
    </script>
@endsection
