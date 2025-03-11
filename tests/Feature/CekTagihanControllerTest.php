<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Tarif;
use App\Models\Periode;
use App\Models\Pemakaian;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CekTagihanControllerTest extends TestCase
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
            'no_hp'     => '08123456789', // Tambahkan no_hp untuk pengujian
        ]);

        // Login sebagai pelanggan
        $this->actingAs($pelangganUser);

        // Siapkan tarif untuk pengujian
        Tarif::create([
            'm3'    => 1000,
            'beban' => 5000,
            'denda' => 1000,
        ]);
    }

    public function testIndexPageIsDisplayed()
    {
        $periode = Periode::first();
        // Membuat pemakaian yang belum dibayar
        Pemakaian::create([
            'penggunaan_awal' => 0,
            'penggunaan_akhir' => 10,
            'jumlah_penggunaan' => 10,
            'user_id' => auth()->user()->id,
            'periode_id' => $periode->id, // Asumsi periode_id 1 ada
            'jumlah_pembayaran' => 10000,
            'status' => 'belum dibayar',
            'batas_bayar'       => '2024-01-30',
        ]);

        $response = $this->get('/cek-tagihan');
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('cek-tagihan.index'); // Memastikan view yang digunakan benar
        $response->assertViewHas('tagihans'); // Memastikan view memiliki data tagihan
    }

    public function testDetailTagihanIsDisplayed()
    {
        $periode = Periode::first();
        // Membuat pemakaian untuk detail tagihan
        $pemakaian = Pemakaian::create([
            'penggunaan_awal'   => 0,
            'penggunaan_akhir'  => 10,
            'jumlah_penggunaan' => 10,
            'user_id'           => auth()->user()->id,
            'periode_id'        => $periode->id, // Asumsi periode_id 1 ada
            'jumlah_pembayaran' => 10000,
            'status'            => 'belum dibayar',
            'batas_bayar'       => '2024-01-30',
        ]);

        $response = $this->get('/cek-tagihan/' . $pemakaian->id); // Ganti dengan route detail yang sesuai
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('cek-tagihan.detail'); // Memastikan view yang digunakan benar
        $response->assertViewHas('tagihan'); // Memastikan view memiliki tagihan yang benar
    }
}
