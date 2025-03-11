<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Saldo;
use App\Models\SaldoHistory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LaporanKeuanganControllerTest extends TestCase
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
        $adminUser = User::create([
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
        // Test if index page is accessible
        $response = $this->get('/laporan-keuangan');
        $response->assertStatus(200); // Check if status is 200
        $response->assertViewIs('laporan-keuangan.index'); // Check if the correct view is returned
    }

    /** @test */
    public function testGetDataWithDateRange()
    {
        // Create some sample data
        $saldo = Saldo::create([
            'saldo' => 10000,
        ]);

        SaldoHistory::create([
            'saldo_id'      => $saldo->id,
            'nominal'       => 20000,
            'keterangan'    => 'sumbangan',
            'status'        => 'masuk',
        ]);

        // Call the route with date range
        $response = $this->withoutMiddleware()->get('/laporan-keuangan/get-data', [
            'tanggal_mulai'   => '2024-01-01',
            'tanggal_selesai' => '2024-12-31',
        ]);

        $response->assertStatus(200); // Check if the response is successful
        $response->assertJsonFragment([
            'nominal' => 20000, // Adjust this based on actual data structure
            'status'  => 'masuk',
        ]); // Ensure the response contains the expected data
    }

    /** @test */
    public function testGetAllData()
    {
        $saldo = Saldo::create([
            'saldo' => 10000,
        ]);

        SaldoHistory::create([
            'saldo_id'      => $saldo->id,
            'nominal'       => 20000,
            'keterangan'    => 'sumbangan',
            'status'        => 'masuk',
        ]);

        // Call the route without date range
        $response = $this->withoutMiddleware()->get('/laporan-keuangan/get-data');

        $response->assertStatus(200); // Check if the response is successful
        $response->assertJsonFragment([
            'nominal' => 20000, // Adjust this based on actual data structure
            'status'  => 'masuk',
        ]); // Ensure the response contains the expected data
    }
}