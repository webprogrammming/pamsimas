<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Saldo;
use App\Models\Tarif;
use App\Models\Periode;
use App\Models\Pemakaian;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PembayaranControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Membuat role secara manual
        Role::create(['role' => 'admin']);
        Role::create(['role' => 'petugas']);
        Role::create(['role' => 'pelanggan']);

        // Membuat pengguna admin untuk pengujian
        $adminUser  = User::create([
            'name'      => 'Admin User',
            'email'     => 'admin@example.com',
            'password'  => bcrypt('password'),
            'role_id'   => 1, // Role admin
        ]);

        // Login sebagai admin
        $this->actingAs($adminUser);
    }

    public function testIndexPageIsDisplayed()
    {
        // Membuat user pelanggan
        $user = User::create([
            'name' => 'Pelanggan Test',
            'email' => 'pelanggan@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3, // Pelanggan
        ]);

        // Membuat periode aktif
        $periode = Periode::create([
            'periode'   => 'Periode 2024',
            'bulan_id'  => 1,
            'tahun_id' => 1,
            'status'    => 'Aktif',
        ]);

        $response = $this->get('/pembayaran');

        // Memastikan response status 200 (sukses)
        $response->assertStatus(200);

        // Memastikan view yang ditampilkan benar
        $response->assertViewIs('pembayaran.index');

        // Memastikan data 'users' dan 'periodes' dikirimkan ke view
        $response->assertViewHas('users');
        $response->assertViewHas('periodes');
    }

    public function testGetTarif()
    {
        // Create necessary tarif
        $tarif = Tarif::first();

        $response = $this->get('/tarif/get-data/1');
        $response->assertStatus(200);
        $response->assertJson([
            'm3'    => $tarif->m3,
            'beban' => $tarif->beban,
            'denda' => $tarif->denda,
        ]);
    }

    public function testPaymentProcess()
    {
        // Create necessary data
        $user = User::create([
            'name'      => 'Pelanggan User',
            'email'     => 'pelanggan@example.com',
            'password'  => bcrypt('password'),
            'role_id'   => 3, // Pelanggan role
        ]);

        $periode = Periode::first();
        $pemakaian = Pemakaian::create([
            'penggunaan_awal'   => 10,
            'penggunaan_akhir'  => 20,
            'jumlah_penggunaan' => 10,
            'jumlah_pembayaran' => 5000,
            'batas_bayar'       => '2024-01-30',
            'user_id'           => $user->id,
            'periode_id'        => $periode->id,
            'status'            => 'belum dibayar',
        ]);

        $response = $this->withoutMiddleware()->post('/pembayaran', [
            'm3'               => 5,
            'beban'            => 10000,
            'pemakaian_id'     => $pemakaian->id,
            'tgl_bayar'        => '2024-01-01',
            'uang_cash'        => 100000,
            'kembalian'        => 80000,
            'denda'            => 2000,
            'jumlah_pembayaran' => 20000,
        ]);

        $response->assertStatus(200); // Check if payment is successful
        $response->assertJson(['message' => 'Tagihan air berhasil dibayar !']);

        // Check if the payment is recorded in the database
        $this->assertDatabaseHas('pembayarans', [
            'pemakaian_id' => $pemakaian->id,
            'subTotal'     => 20000,
        ]);
    }

    public function testCountCustomerMoney()
    {
        $user = User::create([
            'name'      => 'Pelanggan User',
            'email'     => 'pelanggan@example.com',
            'password'  => bcrypt('password'),
            'role_id'   => 3, // Pelanggan role
        ]);

        $periode = Periode::first();

        $pemakaian = Pemakaian::create([
            'penggunaan_awal'   => 10,
            'penggunaan_akhir'  => 20,
            'jumlah_penggunaan' => 10,
            'jumlah_pembayaran' => 5000,
            'batas_bayar'       => '2024-01-30',
            'user_id'           => $user->id,
            'periode_id'        => $periode->id,
            'status'            => 'belum dibayar',
        ]);

        $response = $this->withoutMiddleware()->post('/pembayaran', [
            'm3' => 10,
            'beban' => 10000,
            'pemakaian_id' => $pemakaian->id,
            'tgl_bayar' => now(),
            'uang_cash' => 100000,
            'kembalian' => 0,
            'denda' => 5000,
            'jumlah_pembayaran' => 190000,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Uang cash tidak mencukupi untuk pembayaran!',
        ]);
    }

    public function testPrintPdf()
    {
        // Create necessary data
        $user = User::create([
            'name'      => 'Pelanggan User',
            'email'     => 'pelanggan@example.com',
            'password'  => bcrypt('password'),
            'role_id'   => 3, // Pelanggan role
        ]);

        $periode = Periode::first();
        $pemakaian = Pemakaian::create([
            'penggunaan_awal'   => 10,
            'penggunaan_akhir'  => 20,
            'jumlah_penggunaan' => 10,
            'jumlah_pembayaran' => 5000,
            'batas_bayar'       => '2024-01-30',
            'user_id'           => $user->id,
            'periode_id'        => $periode->id,
            'status'            => 'belum dibayar',
        ]);

        $response = $this->withoutMiddleware()->post('/pembayaran', [
            'm3'               => 5,
            'beban'            => 10000,
            'pemakaian_id'     => $pemakaian->id,
            'tgl_bayar'        => '2024-01-01',
            'uang_cash'        => 100000,
            'kembalian'        => 80000,
            'denda'            => 2000,
            'jumlah_pembayaran' => 20000,
        ]);

        // Call the route to print the payment receipt
        $response = $this->get("/pembayaran/bukti-pembayaran/{$pemakaian->id}");

        $response->assertStatus(200); // Check if the response is successful
        $response->assertHeader('Content-Type', 'application/pdf'); // Ensure it's a PDF response
    }
}
