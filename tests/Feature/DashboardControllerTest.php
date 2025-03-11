<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardControllerTest extends TestCase
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

    /**
     * Test index page for dashboarrd.
     */
    public function testDashboardPageIsDisplayed(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
