<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pembayaran</title>
</head>
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

<body>
    <div class="container">
        <div class="header">
            <h3>LAPORAN PEMBAYARAN TAGIHAN LAYANAN PAMDes</h3>
            <h3>BUMDES SALING SEDOK</h3>
            <h3>KEC. SAKRA KAB. LOMBOK TIMUR</h3>
        </div>

        <hr>

        <div class="row">
            <div class="column">
                <h3 style="text-align: center;">Laporan Pembayaran Tagihan Layanan PAMDes
                    {{ $tanggalMulai && $tanggalSelesai
                        ? \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('j F Y') .
                            ' - ' .
                            \Carbon\Carbon::parse($tanggalSelesai)->translatedFormat('j F Y')
                        : 'Semua Range Tanggal' }}
                </h3>
            </div>
        </div>

        <div class="detail">
            <table id="table_id">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Tagihan Air</th>
                        <th>Sampah</th>
                        <th>Sb. Masjid</th>
                        <th>Denda</th>
                        <th>Tgl. Pembayaran</th>
                        <th>Pelanggan</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $pembayaran)
                        <tr>
                            <td style="text-align: center">{{ $loop->iteration }}</td>
                            <td>{{ $pembayaran->kd_pembayaran }}</td>
                            <td>Rp.
                                {{ number_format($tarif->m3 * $pembayaran->pemakaian->jumlah_penggunaan) }}
                            </td>
                            <td>Rp. {{ number_format($pembayaran->sampah) }}</td>
                            <td>Rp. {{ number_format($pembayaran->masjid) }}</td>
                            <td>Rp. {{ number_format($pembayaran->denda) }}</td>
                            <td>{{ \Carbon\Carbon::parse($pembayaran->tgl_bayar)->translatedFormat('j F Y') }}</td>
                            <td>{{ $pembayaran->pemakaian->user->name }}</td>
                            <td>Rp. {{ number_format($pembayaran->subTotal, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</body>

</html>
