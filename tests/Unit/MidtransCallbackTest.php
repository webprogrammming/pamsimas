<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Saldo;
use App\Models\Tarif;
use App\Models\Periode;
use App\Models\Pemakaian;
use App\Models\Pembayaran;
use App\Models\SaldoHistory;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MidtransCallbackTest extends TestCase
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

        // Inisialisasi saldo dan periode
        Saldo::create(['saldo' => 0]);
    }

    /** @test */
    public function testMidtransCallbackSuccess()
    {
        // Simulate a request from Midtrans
        $request = new Request([
            'order_id'           => '1_' . time(),
            'status_code'        => 200,
            'gross_amount'       => 15000,
            'transaction_status' => 'settlement',
        ]);;

        $periode = Periode::first();
        $this->pemakaian = Pemakaian::create([
            'penggunaan_awal'   => 10,
            'penggunaan_akhir'  => 20,
            'jumlah_penggunaan' => 10,
            'jumlah_pembayaran' => 5000,
            'batas_bayar'       => '2024-01-30',
            'user_id'           => auth()->user()->id,
            'periode_id'        => $periode->id,
            'status'            => 'lunas',
        ]);

        $response = $this->post('/midtrans-callback', $request->all());

        // Check if the payment status is updated to "lunas"
        $this->pemakaian->refresh();
        $this->assertEquals('lunas', $this->pemakaian->status);
    }
}
