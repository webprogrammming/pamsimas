<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LihatPemakaianControllerTest extends TestCase
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
        $response = $this->get('/lihat-pemakaian');
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('lihat-pemakaian.index'); // Memastikan view yang digunakan benar
    }
}