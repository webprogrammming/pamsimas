<?php

namespace Tests\Unit;

use Mockery;
use Midtrans\Snap;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MidtransPaymentProccessTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a role and a user for testing
        $role = Role::create(['role' => 'pelanggan']);
        $pelangganUser = User::create([
            'name'      => 'Test Pelanggan',
            'email'     => 'test@gmail.com',
            'password'  => bcrypt('password'),
            'role_id'   => $role->id,
            'no_hp'     => '08123456789', // Tambahkan no_hp untuk pengujian
        ]);

        // Login sebagai pelanggan
        $this->actingAs($pelangganUser);
    }

    public function testMidtransPaymentProccess()
    {
        // Mocking Midtrans Snap::getSnapToken
        // Moccking berfungsi untuk membuat simulasi token midtrans snap agar tidak token asli yang di tampilkan
        $mockSnap = Mockery::mock('alias:Midtrans\Snap');
        $mockSnap->shouldReceive('getSnapToken')
            ->once()
            ->andReturn('mocked_snap_token');

        // Data yang digunakan untuk pengujian
        $pemakaian_id = 123;
        $subTotal = 50000;

        // Lakukan permintaan ke paymentProcess method
        $response = $this->withoutMiddleware()->postJson('/cek-tagihan/bayar', [
            'pemakaian_id'     => $pemakaian_id,
            'jumlah_pembayaran' => $subTotal,
        ]);

        // Pastikan status HTTP 200 (OK)
        $response->assertStatus(200);

        // Pastikan respons berisi snapToken yang dimock
        $response->assertJson([
            'snapToken' => 'mocked_snap_token',
        ]);
    }

    protected function tearDown(): void
    {
        // Bersihkan Mockery setelah setiap pengujian
        Mockery::close();
        parent::tearDown();
    }
}
