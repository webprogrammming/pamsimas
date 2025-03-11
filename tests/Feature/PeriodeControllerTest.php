<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Periode;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class PeriodeControllerTest extends TestCase
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

        Periode::create([
            'id' => 1, // Pastikan ID sesuai untuk pengujian
            'periode' => 'September 2024',
            'bulan_id' => 9,
            'tahun_id' => 1,
            'status' => 'aktif'
        ]);
    }

    public function testIndexPageIsDisplayed()
    {
        $response = $this->get('/periode');
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('periode.index'); // Memastikan view yang digunakan benar
    }

    public function testCreatePageIsDisplayed()
    {
        $response = $this->get('/periode/create');
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('periode.create'); // Memastikan view yang digunakan benar
    }

    public function testStoreNewPeriode()
    {
        // Data input untuk request store
        $data = [
            'periode' => 'Periode Baru',
            'bulan_id' => 1, // Asumsi ID 1 sudah ada di table bulan
            'tahun_id' => 1,  // Asumsi ID 1 sudah ada di table tahun
            'status'   => 'tidak aktif'
        ];

        // Menggunakan withoutMiddleware untuk menonaktifkan CSRF
        $response = $this->withoutMiddleware()->post('/periode', $data);

        // Cek apakah redirect setelah sukses
        $response->assertStatus(302);
        $response->assertRedirect('/periode');

        // Cek apakah data tersimpan di database
        $this->assertDatabaseHas('periodes', [
            'periode' => 'Periode Baru',
            'bulan_id' => 1,
            'tahun_id' => 1,
            'status'   => 'tidak aktif'
        ]);
    }

    public function testEditPageIsDisplayed()
    {
        // Asumsi periode dengan ID 1 sudah ada di database
        $periode = Periode::find(1);

        $response = $this->get("/periode/{$periode->id}/edit");
        $response->assertStatus(200); // Cek apakah respons status 200
        $response->assertViewIs('periode.edit'); // Memastikan view yang digunakan benar
        $response->assertViewHas('periode'); // Pastikan view menerima variabel periode
    }

    public function testUpdatePeriode()
    {
        $periode = Periode::first();

        $data = [
            'id' => 1,
            'periode' => 'September 2024',
            'bulan_id' => 9,
            'tahun_id' => 1,
            'status' => 'aktif'
        ];

        // Mengupdate periode dengan mengabaikan middleware
        $response = $this->withoutMiddleware()->put("/periode/{$periode->id}", $data);

        // Cek apakah redirect setelah update
        $response->assertStatus(302);
        $response->assertRedirect('/periode');

        // Cek apakah data di database sudah diperbarui
        $this->assertDatabaseHas('periodes', [
            'id' => $periode->id,
            'periode' => 'September 2024',
            'bulan_id' => 9,
            'tahun_id' => 1,
            'status' => 'aktif'
        ]);
    }

    public function testDeletePeriode()
    {
        $periode = Periode::first();

        // Menghapus periode dengan mengabaikan middleware
        $response = $this->withoutMiddleware()->delete("/periode/{$periode->id}");

        // Cek apakah redirect setelah delete
        $response->assertStatus(302);
        $response->assertRedirect(url()->previous());

        // Cek apakah data sudah dihapus dari database
        $this->assertDatabaseMissing('periodes', [
            'id' => $periode->id
        ]);
    }
}
