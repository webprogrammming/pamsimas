<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Saldo;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SaldoMasukControllerTest extends TestCase
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
        $response = $this->get('/saldo-masuk');
        $response->assertStatus(200); // Check if status is 200
        $response->assertViewIs('saldo-masuk.index'); // Check if the correct view is used
    }

    public function testCreatePageIsDisplayed()
    {
        $response = $this->get('/saldo-masuk/create');
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('saldo-masuk.create'); // Memastikan view yang digunakan benar
    }

    public function testStoreNewPemasukanSaldo()
    {
        $saldo = Saldo::first();

        // Data input untuk request store
        $data = [
            'saldo_id'  => $saldo->id,
            'nominal'   => 100000,
            'keterangan' => 'Sumbangan',
            'status'    => 'masuk'
        ];

        // Menggunakan withoutMiddleware untuk menonaktifkan CSRF
        $response = $this->withoutMiddleware()->post('/saldo-masuk', $data);

        // Cek apakah redirect setelah sukses
        $response->assertStatus(302);
        $response->assertRedirect('/saldo-masuk');

        // Cek apakah data tersimpan di database
        $this->assertDatabaseHas('saldo_histories', [
            'saldo_id'  => $saldo->id,
            'nominal'   => 100000,
            'keterangan' => 'Sumbangan',
            'status'    => 'masuk'
        ]);
    }
}
