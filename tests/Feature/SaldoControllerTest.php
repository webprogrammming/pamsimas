<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SaldoControllerTest extends TestCase
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
        $response = $this->get('/saldo');
        $response->assertStatus(200); // Check if status is 200
        $response->assertViewIs('saldo.index'); // Check if the correct view is used
    }
}