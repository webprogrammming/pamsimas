<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Periode;
use App\Models\Pemakaian;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LaporanPembayaranTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles manually
        Role::create(['role' => 'admin']);
        Role::create(['role' => 'petugas']);
        Role::create(['role' => 'pelanggan']);

        // Create admin user for testing
        $adminUser  = User::create([
            'name'      => 'Admin User',
            'email'     => 'admin@example.com',
            'password'  => bcrypt('password'),
            'role_id'   => 1, // Admin role
        ]);

        // Log in as admin
        $this->actingAs($adminUser);
    }

    /** @test */
    public function testIndexPageIsDisplayed()
    {
        $response = $this->get('/laporan-pembayaran');
        $response->assertStatus(200); // Check if status is 200
        $response->assertViewIs('laporan-pembayaran.index'); // Check if the correct view is used
    }

    /** @test */
    public function testGetDataWithDateRange()
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
            'subTotal'          => 20000,
        ]);

        // Call the route with a date range
        $response = $this->withoutMiddleware()->get('/laporan-pembayaran/get-data', [
            'tanggal_mulai'   => '2024-01-01',
            'tanggal_selesai' => '2024-12-31',
        ]);

        $response->assertStatus(200); // Check if the response is successful
        $response->assertJsonFragment([
            'tgl_bayar' => '2024-01-01',
            'subTotal'  => 20000,
        ]); // Check if the response includes the expected data
    }

    /** @test */
    public function testGetAllData()
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
            'subTotal'          => 20000,
        ]);

        // Call the route without date range
        $response = $this->withoutMiddleware()->get('/laporan-pembayaran/get-data');

        $response->assertStatus(200); // Check if the response is successful
        $response->assertJsonFragment([
            'tgl_bayar' => '2024-01-01',
            'subTotal'  => 20000,
        ]); // Check if the response includes the expected data
    }
}
