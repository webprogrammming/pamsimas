<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PelangganControllerTest extends TestCase
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

        // Membuat pengguna pelanggan untuk pengujian
        User::create([
            'name'      => 'Pelanggan Test',
            'email'     => 'pelanggan_test@example.com',
            'no_hp'     => '081229248179',
            'tgl_pasang' => '2024-10-17',
            'password'  => bcrypt('password'),
            'role_id'   => 3, // Role pelanggan
        ]);

        // Login sebagai admin
        $this->actingAs($adminUser);
    }

    /**
     * Test index page (listing of pelanggans).
     */
    public function testIndexPageIsDisplayed()
    {
        $response = $this->get('/pelanggan');
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('pelanggan.index'); // Memastikan view yang digunakan benar
    }

    /**
     * Test create page (form for adding new pelanggan).
     */
    public function testCreatePageIsDisplayed()
    {
        $response = $this->get('/pelanggan/create');
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('pelanggan.create'); // Memastikan view yang digunakan benar
    }

    /**
     * Test store (adding a new pelanggan).
     */
    public function testStoreNewPelanggan()
    {
        // Simpan gambar qrcode di storage
        Storage::fake('public');

        $response = $this->withoutMiddleware()->post('/pelanggan', [
            'name'              => 'John Doe',
            'email'             => 'john@gmail.com',
            'no_hp'             => '081234567890',
            'tgl_pasang'        => '2023-10-10',
            'password'          => 'secret',
            'confirmPassword'   => 'secret',
            'role_id'           => 3
        ]);

        // Cek redirect
        $response->assertStatus(302);
        $response->assertRedirect('/pelanggan');
        $response->assertSessionHas('success', 'Berhasil menambahkan pelanggan baru');

        // Cek apakah data pelanggan tersimpan di database
        $this->assertDatabaseHas('users', [
            'email' => 'pelanggan_test@example.com',
            'no_hp' => '081229248179'
        ]);
    }

    /**
     * Test show (display a specific pelanggan).
     */
    public function testShowDetailPelanggan()
    {
        $pelanggan = User::where('role_id', 3)->first();

        $response = $this->get("/pelanggan/{$pelanggan->id}");

        $response->assertStatus(200);
        $response->assertViewHas('pelanggan', $pelanggan);
    }

    /**
     * Test edit page (form for editing a pelanggan).
     */
    public function testEditPageIsDisplayed()
    {
        $pelanggan = User::where('role_id', 3)->first();

        $response = $this->get("/pelanggan/{$pelanggan->id}/edit");

        $response->assertStatus(200);
        $response->assertViewHas('pelanggan', $pelanggan);
    }

    /**
     * Test update (updating a pelanggan's data).
     */
    public function testUpdatePelanggan()
    {
        $pelanggan = User::where('role_id', 3)->first();

        $response = $this->withoutMiddleware()->put("/pelanggan/{$pelanggan->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'no_hp' => '081234567891',
            'tgl_pasang' => '2024-01-01',
        ]);

        // Cek redirect
        $response->assertStatus(302);
        $response->assertRedirect('/pelanggan');
        $response->assertSessionHas('success', 'Berhasil memperbarui data pelanggan');

        // Cek apakah data pelanggan telah ter-update di database
        $this->assertDatabaseHas('users', [
            'id' => $pelanggan->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'no_hp' => '081234567891'
        ]);
    }

    /**
     * Test destroy (deleting a pelanggan).
     */
    public function testDeletePelanggan()
    {
        $pelanggan = User::where('role_id', 3)->first();
        $response = $this->withoutMiddleware()->delete("/pelanggan/{$pelanggan->id}");

        // Cek redirect
        $response->assertStatus(302);
        $response->assertRedirect(url()->previous());
        $response->assertSessionHas('success', 'Berhasil menghapus pelanggan !');

        // Cek apakah data pelanggan sudah dihapus dari database
        $this->assertDatabaseMissing('users', [
            'id' => $pelanggan->id
        ]);
    }
}
