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
        Schema::create('bengkel', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('gambar_pratinjau');
            $table->string('nomer_telepon');
            $table->string('nama_cs');
            $table->text('alamat');
            $table->boolean('status_operasional');
            $table->foreignId('kota_id')->constrained('kota')->cascadeOnDelete();
            $table->boolean('status_kapasitas');
            $table->string('slug')->unique();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bengkel');
    }
};
