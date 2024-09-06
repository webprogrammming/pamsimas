<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Keuangan</title>
    <style>
        .container {
            border: 1px solid black;
            padding: 20px;
        }

        .header {
            text-align: center;
        }

        .h3 {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .column {
            text-align: center;
            width: 100%;
            margin-bottom: 15px;
        }

        .detail {
            margin-top: 15px;
            padding-left: 10px;
        }

        .row {
            margin-top: 10px;
            margin-bottom: 20px;
            padding: 30px;
        }

        table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            /* Menyatukan border antar-sel */
        }

        table,
        th,
        td {
            border: 1px solid black;
            /* Menampilkan border pada tabel, th, dan td */
        }

        th,
        td {
            padding: 10px;
            /* Menambahkan padding di dalam sel */
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Laporan Pembayaran Tagihan Air Pamsimas</h2>
            <p>Desa Karangmulyo, Kecamatan Purwodadi, Kabupaten Purworejo, Jawa Tengah 54173</p>
        </div>
        <hr>
        <div class="row">
            <div class="column">
                <h3 style="text-align: center;">Laporan Pembayaran Air
                    {{ $tanggalMulai && $tanggalSelesai
                        ? \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('j F Y') .
                            ' - ' .
                            \Carbon\Carbon::parse($tanggalSelesai)->translatedFormat('j F Y')
                        : 'Semua Range Tanggal' }}
                </h3>
            </div>
            <div class="col">
                <table id="table_id" class="display">
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
                        @foreach ($data as $history)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('j F Y') }}</td>
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
                    <tfoot>
                        <tr>
                            <td colspan="4"><strong>Saldo Saat Ini : </strong></td>
                            <td><strong>Rp. {{ number_format($saldo->saldo, 2, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
