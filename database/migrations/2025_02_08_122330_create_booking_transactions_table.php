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
        Schema::create('kelola_pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('trx_id');
            $table->string('nomer_telepon');
            $table->string('bukti');
            $table->unsignedBigInteger('total_bayar');
            $table->boolean('status_pembayaran');
            $table->date('waktu_mulai');
            $table->time('jam_mulai');
            $table->foreignId('servis_mobil_id')->constrained('servis_mobil')->cascadeOnDelete();
            $table->foreignId('bengkel_id')->constrained('bengkel')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelola_pemesanan');
    }
};
