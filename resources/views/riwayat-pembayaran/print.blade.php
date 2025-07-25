<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran Tagihan PAMDes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
        }

        .struk {
            width: 100%;
            margin: 10px auto;
            border: 2px solid #000080;
            background-color: #d7ebff;
            padding: 10px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .highlight {
            color: red;
            font-weight: bold;
            font-size: 18px;
        }

        .small {
            font-size: 12px;
        }

        hr {
            border: 1px dashed #000;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <div class="struk">
        <table>
            <tr>
                <td style="width: 20%;"><img src="data:image/png;base64,{{ $logoLombok }}" alt="Logo" width="60">
                </td>
                <td class="center bold" style="width: 60%;">
                    "BUMDES SALING SEDOK"<br>
                    DESA PERESAK<br>
                    KEC. SAKRA KAB. LOMBOK TIMUR
                </td>
                <td class="right" style="width: 20%;"><img src="data:image/png;base64,{{ $logoBumdes }}"
                        alt="Logo BUMDES" width="60"></td>
            </tr>
        </table>

        <hr>

        <!-- Info & Tagihan (2 kolom) -->
        <table style="width: 100%;">
            <tr>
                <!-- Kolom Kiri -->
                <td style="width: 50%; vertical-align: top;">
                    <table>
                        <tr>
                            <td style="width: 40%;">Bulan</td>
                            <td style="width: 5%;">:</td>
                            <td><strong>{{ $pembayaran->pemakaian->periode->periode }}</strong></td>
                        </tr>
                        <tr>
                            <td>No. ID</td>
                            <td>:</td>
                            <td>{{ $pembayaran->pemakaian->user->no_pelanggan }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ $pembayaran->pemakaian->user->name }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>PERESAK IDIK</td>
                        </tr>
                        <tr>
                            <td>PAMDes</td>
                            <td>:</td>
                            <td>"SALING SEDOK"</td>
                        </tr>
                        <tr>
                            <td>Meter Awal</td>
                            <td>:</td>
                            <td>{{ $pembayaran->pemakaian->penggunaan_awal }} M<sup>3</sup></td>
                        </tr>
                        <tr>
                            <td>Meter Akhir</td>
                            <td>:</td>
                            <td>{{ $pembayaran->pemakaian->penggunaan_akhir }} M<sup>3</sup></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td><strong>{{ $pembayaran->pemakaian->status }}</strong></td>
                        </tr>
                    </table>
                </td>

                <!-- Kolom Kanan -->
                <td style="width: 50%; vertical-align: top;">
                    <table>
                        <tr>
                            <td style="width: 40%;">Pakai</td>
                            <td style="width: 5%;">:</td>
                            <td>{{ $pembayaran->pemakaian->jumlah_penggunaan }} M<sup>3</sup></td>
                        </tr>
                        <tr>
                            <td>Total Tagihan Air</td>
                            <td>:</td>
                            <td>Rp.
                                {{ number_format($tarif->m3 * $pembayaran->pemakaian->jumlah_penggunaan, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td>Beban</td>
                            <td>:</td>
                            <td>Rp. {{ number_format($pembayaran->beban) }}</td>
                        </tr>
                        <tr>
                            <td>Sampah</td>
                            <td>:</td>
                            <td>Rp. {{ number_format($pembayaran->sampah) }}</td>
                        </tr>
                        <tr>
                            <td>Sb. Masjid</td>
                            <td>:</td>
                            <td>Rp. {{ number_format($pembayaran->masjid) }}</td>
                        </tr>
                        <tr>
                            <td>Denda</td>
                            <td>:</td>
                            <td>Rp. {{ number_format($pembayaran->denda) }}</td>
                        </tr>
                        <tr>
                            <td style="color: red; font-weight: bold;">Total Bayar</td>
                            <td style="color: red; font-weight: bold;">:</td>
                            <td style="color: red; font-weight: bold;">Rp. {{ number_format($pembayaran->subTotal) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Pembayaran</td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($pembayaran->tgl_bayar)->translatedFormat('j F Y') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>


        <hr>

        <!-- Catatan dan Kontak (footer kiri dan kanan) -->
        <table>
            <tr>
                <td style="width: 60%; vertical-align: top;" class="small">
                    Pastikan Anda telah membayar air<br>
                    sebelum tanggal 25 setiap bulan...!!!!!<br><br>
                    Rusak/Bocor:<br>
                    H. MARWAN: 0878645410124<br>
                    AWAN: 087754320725
                </td>
                <td style="width: 40%; text-align: center; vertical-align: top;">
                    <strong>KETUA PAMDES</strong><br><br><br>
                    H. Marwan Hadi
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
