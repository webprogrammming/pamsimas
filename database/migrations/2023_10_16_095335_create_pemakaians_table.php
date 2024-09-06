<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemakaians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('penggunaan_awal');
            $table->bigInteger('penggunaan_akhir');
            $table->bigInteger('jumlah_penggunaan');
            $table->bigInteger('jumlah_pembayaran')->nullable();
            $table->date('batas_bayar');
            $table->enum('status', ['belum dibayar', 'lunas'])->default('belum dibayar');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakaians');
    }
};