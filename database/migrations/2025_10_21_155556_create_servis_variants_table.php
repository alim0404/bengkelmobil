<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servis_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_mobil_id')->constrained('servis_mobil')->cascadeOnDelete();
            $table->string('nama');
            $table->unsignedBigInteger('harga');
            $table->text('deskripsi')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servis_variants');
    }
};