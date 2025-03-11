<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class TahunControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $role       = Role::create(['role' => 'admin']);
        $adminUser  = User::create([
            'name'      => 'test',
            'email'     => 'test@gmail.com',
            'password'  => bcrypt('password'),
            'role_id'   => $role->id,
        ]);

        // Login sebagai admin
        $this->actingAs($adminUser);
    }

    public function testIndexPageIsDisplayed()
    {
        $response = $this->get('/tahun');
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('tahun.index'); // Memastikan view yang digunakan benar
    }

    public function testCreatePageIsDisplayed()
    {
        $response = $this->get('/tahun/create');
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('tahun.create'); // Memastikan view yang digunakan benar
    }

    public function testStoreNewTahun()
    {
        // Data input untuk request store
        $data = [
            'tahun' => '2024',
        ];

        // Menggunakan withoutMiddleware untuk menonaktifkan CSRF
        $response = $this->withoutMiddleware()->post('/tahun', $data);

        // Cek apakah redirect setelah sukses
        $response->assertStatus(302);
        $response->assertRedirect('/tahun');

        // Cek apakah data tersimpan di database
        $this->assertDatabaseHas('tahuns', [
            'tahun' => '2024',
        ]);
    }

    public function testEditPageIsDisplayed()
    {
        // Asumsi tahun dengan ID 1 sudah ada di database
        $tahun = Tahun::find(1);

        $response = $this->get("/tahun/{$tahun->id}/edit");
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('tahun.edit'); // Memastikan view yang digunakan benar
        $response->assertViewHas('tahun'); // Pastikan view menerima variabel tahun
    }

    public function testUpdateTahun()
    {
        $tahun = Tahun::first();

        $data = [
            'tahun' => '2024',
        ];

        // Mengupdate tahun dengan mengabaikan middleware
        $response = $this->withoutMiddleware()->put("/tahun/{$tahun->id}", $data);

        // Cek apakah redirect setelah update
        $response->assertStatus(302);
        $response->assertRedirect('/tahun');

        // Cek apakah data di database sudah diperbarui
        $this->assertDatabaseHas('tahuns', [
            'tahun'     => '2024',
        ]);
    }

    public function testDeleteTahun()
    {
        $tahun = Tahun::first();

        // Menghapus tahun dengan mengabaikan middleware
        $response = $this->withoutMiddleware()->delete("/tahun/{$tahun->id}");

        // Cek apakah redirect setelah delete
        $response->assertStatus(302);
        $response->assertRedirect(url()->previous());

        // Cek apakah data sudah dihapus dari database
        $this->assertDatabaseMissing('tahuns', [
            'id' => $tahun->id
        ]);
    }
}