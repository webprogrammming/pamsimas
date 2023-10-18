<div class="container">
    <div class="header">
        <h1>Payment Receipt</h1>
    </div>
    <div class="receipt">
        <div class="details">
            <p><strong>Pemakaian Akhir:</strong> {{ $pemakaian->penggunaan_akhir }}</p>
            <p><strong>Jumlah Penggunaan :</strong> {{ $pemakaian->jumlah_penggunaan }}</p>

            <p><strong>Payment ID:</strong> {{ $pembayaran->kd_pembayaran }}</p>
            <p><strong>Date:</strong> {{ $pembayaran->tgl_bayar }}</p>
            <p><strong>Customer Name:</strong> {{ $pembayaran->pemakaian->user->name }}</p>
            <p><strong>Customer No:</strong> {{ $pembayaran->pemakaian->user->no_pelanggan }}</p>
            <p><strong>Uang cash:</strong> {{ $pembayaran->uang_cash }}</p>
            <p><strong>Kembalian:</strong> {{ $pembayaran->kembalian }}</p>

            <p>m3 = {{ $tarif_m3 }}</p>
            <p>denda = {{ $denda }}</p>
            <p>Subtotal = {{ $subTotal }}</p>

        </div>
    </div>
</div>
