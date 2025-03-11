<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Tarif;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TarifControllerTest extends TestCase
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
        $response = $this->get('/tarif');
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('tarif.index'); // Memastikan view yang digunakan benar
    }

    public function testEditPageIsDisplayed()
    {
        // Asumsi tarif dengan ID 1 sudah ada di database
        $tarif = Tarif::find(1);

        $response = $this->get("/tarif/{$tarif->id}/edit");
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('tarif.edit'); // Memastikan view yang digunakan benar
        $response->assertViewHas('tarif'); // Pastikan view menerima variabel tarif
    }

    public function testUpdateTarif()
    {
        $tarif = Tarif::first();

        $data = [
            'id'    => 1,
            'm3'    => '1500',
            'beban' => '5000',
            'denda' => '5000',
        ];

        // Mengupdate tarif dengan mengabaikan middleware
        $response = $this->withoutMiddleware()->put("/tarif/{$tarif->id}", $data);

        // Cek apakah redirect setelah update
        $response->assertStatus(302);
        $response->assertRedirect('/tarif');

        // Cek apakah data di database sudah diperbarui
        $this->assertDatabaseHas('tarifs', [
            'id'    => $tarif->id,
            'm3'    => '1500',
            'beban' => '5000',
            'denda' => '5000',
        ]);
    }
}
