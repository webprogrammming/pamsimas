<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Saldo;
use App\Models\Tarif;
use App\Models\Periode;
use App\Models\Pemakaian;
use App\Models\Pembayaran;
use App\Models\SaldoHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CatatPemakaianControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Membuat role secara manual
        Role::create(['role' => 'admin']);
        Role::create(['role' => 'petugas']);
        Role::create(['role' => 'pelanggan']);

        // Membuat pengguna petugas untuk pengujian
        $petugasUser = User::create([
            'name'      => 'Petugas Test',
            'email'     => 'petugas@example.com',
            'password'  => bcrypt('password'),
            'role_id'   => 2, // Role petugas
        ]);

        // Login sebagai petugas
        $this->actingAs($petugasUser);
    }

    public function testIndexPageIsDisplayed()
    {
        $response = $this->get('/catat-pemakaian'); // Sesuaikan dengan route yang sesuai

        $response->assertStatus(200);
        $response->assertViewIs('catat-pemakaian.index');
        $response->assertViewHas('users');
        $response->assertViewHas('periodes');
    }

    public function testGetDataPelanggan()
    {
        $user = User::create([
            'name'      => 'Pelanggan User',
            'email'     => 'pelanggan@example.com',
            'password'  => bcrypt('password'),
            'role_id'   => 3,
        ]);

        $periode = Periode::first(); // Pastikan ada periode
        $pemakaian = Pemakaian::create([
            'penggunaan_awal'   => 10,
            'penggunaan_akhir'  => 20,
            'jumlah_penggunaan' => 10,
            'jumlah_pembayaran' => 5000,
            'batas_bayar'       => '2024-01-30',
            'user_id'           => $user->id,
            'periode_id'        => $periode->id,
            'status'            => 'belum dibayar',
        ]);

        $response = $this->get('/catat-pemakaian/get-data/' . $user->id);

        $response->assertStatus(200);
        $response->assertJson([
            'penggunaan_awal'       => 10, // Asumsi Anda ingin data yang benar
            'penggunaan_akhir'      => 20,
            'jumlah_penggunaan'     => 10,
            'user_id'               => $user->id,
        ]);
    }

    public function testStoreTagihan()
    {
        // Membuat pengguna pelanggan
        $user = User::create([
            'name'      => 'Pelanggan User',
            'email'     => 'pelanggan@example.com',
            'password'  => bcrypt('password'),
            'role_id'   => 3,
        ]);

        // Membuat periode aktif
        $periode = Periode::first(); // Pastikan ada periode

        // Menyiapkan data yang akan dikirim ke method store
        $data = [
            'penggunaan_awal'   => 0,
            'penggunaan_akhir'  => 10,
            'jumlah_penggunaan' => 10,
            'user_id'           => $user->id,
            'periode_id'        => $periode->id, // Menggunakan periode yang ada
            'batas_bayar'       => '2024-01-30',
        ];

        // Mengirim permintaan POST ke route store
        $response = $this->withoutMiddleware()->post('/catat-pemakaian', $data);

        // Memastikan redirect berhasil
        $response->assertRedirect();
        // Memastikan data tersimpan di database
        $this->assertDatabaseHas('pemakaians', [
            'user_id'           => $user->id,
            'jumlah_penggunaan' => 10,
        ]);
    }


    public function testStoreValidationFails()
    {
        $data = [
            // Tidak menyertakan semua field yang wajib
        ];

        $response = $this->withoutMiddleware()->post('/catat-pemakaian', $data); // Sesuaikan dengan route yang sesuai

        $response->assertSessionHasErrors(['penggunaan_awal', 'penggunaan_akhir', 'jumlah_penggunaan', 'user_id', 'periode_id']);
    }

    // public function testGetDataPelanggan()
    // {
    //     $user = User::create([
    //         'name'  => 'Pelanggan User',
    //         'email' => 'pelanggan@example.com',
    //         'password' => bcrypt('password'),
    //         'role_id' => 3,
    //         'no_pelanggan' => '123456', // Tambahkan no_pelanggan untuk pengujian
    //     ]);

    //     Pemakaian::create([
    //         'penggunaan_awal' => 0,
    //         'penggunaan_akhir' => 10,
    //         'jumlah_penggunaan' => 10,
    //         'user_id' => $user->id,
    //         'periode_id' => 1,
    //         'jumlah_pembayaran' => 1000,
    //         'status' => 'belum dibayar',
    //         'batas_bayar'       => '2024-01-30',
    //     ]);

    //     $response = $this->get('/catat-pemakaian/get-data-pelanggan?result=123456'); // Sesuaikan dengan route yang sesuai

    //     $response->assertStatus(200);
    //     $response->assertJson([
    //         'id' => $user->id,
    //         'user_id' => $user->id,
    //         'penggunaan_akhir' => 10,
    //     ]);
    // }
}
