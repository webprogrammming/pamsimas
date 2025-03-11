<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Tarif;
use App\Models\Periode;
use Barryvdh\DomPDF\PDF;
use App\Models\Pemakaian;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class TagihanTerbayarControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['role' => 'pelanggan']);
        $pelangganUser = User::create([
            'name'      => 'Test Pelanggan',
            'email'     => 'test@gmail.com',
            'password'  => bcrypt('password'),
            'role_id'   => $role->id,
            'no_hp'     => '08123456789',
        ]);

        // Login sebagai pelanggan
        $this->actingAs($pelangganUser);

        // Siapkan tarif untuk pengujian
        Tarif::create([
            'm3' => 1000,
            'beban' => 5000,
            'denda' => 1000,
        ]);
    }

    public function testIndexPageIsDisplayed()
    {
        $response = $this->get('/tagihan-terbayar');
        $response->assertStatus(200); // Pastikan respons status 200
        $response->assertViewIs('tagihan-terbayar.index'); // Memastikan view yang digunakan adalah tagihan-terbayar.index
    }

    public function testGetRiwayatPembayaranWithFilter()
    {
        $periode = Periode::first();
        $pemakaian = Pemakaian::create([
            'penggunaan_awal'   => 10,
            'penggunaan_akhir'  => 20,
            'jumlah_penggunaan' => 10,
            'jumlah_pembayaran' => 5000,
            'batas_bayar'       => '2024-01-30',
            'user_id'           => auth()->user()->id,
            'periode_id'        => $periode->id,
            'status'            => 'belum dibayar',
        ]);

        $pembayaran = Pembayaran::create([
            'kd_pembayaran'     => 'INV-192910',
            'm3'               => 5,
            'beban'            => 10000,
            'pemakaian_id'     => $pemakaian->id,
            'tgl_bayar'        => '2024-01-01',
            'uang_cash'        => 100000,
            'kembalian'        => 80000,
            'denda'            => 2000,
            'jumlah_pembayaran' => 20000,
            'subTotal'         => 20000,
        ]);

        // Set tanggal filter
        $tanggalMulai = now()->subDays(7)->format('Y-m-d');
        $tanggalSelesai = now()->format('Y-m-d');

        $response = $this->getJson('/tagihan-terbayar/get-data', [
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
        ]);

        $response->assertStatus(200); // Pastikan respons status 200
        $response->assertJsonStructure([ // Cek apakah data dikembalikan dalam bentuk JSON dengan struktur yang diharapkan
            '*' => [
                'pemakaian_id',
                'kd_pembayaran',
                'tgl_bayar',
                'subTotal',
            ]
        ]);
    }

    public function testGetRiwayatPembayaranWithoutFilter()
    {
        // Membuat data pembayaran
        $pemakaian = Pemakaian::create([
            'penggunaan_awal' => 0,
            'penggunaan_akhir' => 10,
            'jumlah_penggunaan' => 10,
            'user_id' => auth()->user()->id,
            'periode_id' => 1,
            'jumlah_pembayaran' => 10000,
            'status' => 'lunas',
            'batas_bayar'       => '2024-01-30',
        ]);

        $pembayaran = Pembayaran::create([
            'pemakaian_id' => $pemakaian->id,
            'm3' => 1000,
            'beban' => 5000,
            'kd_pembayaran' => 'INV-12345',
            'tgl_bayar' => now(),
            'uang_cash' => 10000,
            'kembalian' => 0,
            'denda' => 0,
            'subTotal' => 10000,
        ]);

        $response = $this->getJson('/tagihan-terbayar/get-data');

        $response->assertStatus(200); // Pastikan respons status 200
        $response->assertJsonStructure([ // Cek apakah data dikembalikan dalam bentuk JSON
            '*' => [
                'pemakaian_id',
                'kd_pembayaran',
                'tgl_bayar',
                'subTotal',
            ]
        ]);
    }

    public function testPrintReturnsPdf()
    {
        // Membuat data pemakaian
        $pemakaian = Pemakaian::create([
            'penggunaan_awal' => 0,
            'penggunaan_akhir' => 10,
            'jumlah_penggunaan' => 10,
            'user_id' => auth()->user()->id,
            'periode_id' => 1,
            'jumlah_pembayaran' => 10000,
            'status' => 'lunas',
            'batas_bayar' => '2024-01-30',
        ]);

        // Membuat data pembayaran
        $pembayaran = Pembayaran::create([
            'pemakaian_id' => $pemakaian->id,
            'm3' => 1000,
            'beban' => 5000,
            'kd_pembayaran' => 'INV-12345',
            'tgl_bayar' => now(),
            'uang_cash' => 10000,
            'kembalian' => 0,
            'denda' => 0,
            'subTotal' => 10000,
        ]);

        // Mengirimkan permintaan GET ke endpoint untuk mencetak PDF
        $response = $this->get('/tagihan-terbayar/print/' . $pembayaran->id);

        // Memastikan respons status adalah 200
        $response->assertStatus(200);

        // Memastikan header menunjukkan file PDF dihasilkan
        $response->assertHeader('content-type', 'application/pdf');

        // Memastikan respons berisi konten PDF (sebagai contoh, bisa mengecek sebagian kecil dari string biner PDF)
        $this->assertStringContainsString('%PDF', $response->getContent());
    }
}
