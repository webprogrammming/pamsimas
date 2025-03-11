<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Keuangan</title>
    <style>
        .container {
            text-align: center;
            margin: auto;
        }

        .column {
            text-align: center;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        table {
            margin: auto;
            width: 100%;
        }

        tr {
            text-align: left;
        }

        table,
        th,
        td {
            border-collapse: collapse;
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
        }

        th,
        tfoot {
            background-color: gainsboro;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="column">
                <h2>PAMSIMAS TIRTOMULYO</h2>
                <p>Desa Kalirejo, Kecamatan Wirosari, Kabupaten Grobogan, Jawa Tengah</p>
                <hr style="width: 85%; text-align: center;">
                <h3 style="text-align: center;">Laporan Keuangan
                    {{ $tanggalMulai && $tanggalSelesai
                        ? date('d-m-Y', strtotime($tanggalMulai)) . ' - ' . date('d-m-Y', strtotime($tanggalSelesai))
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
