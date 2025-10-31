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
        Schema::create('bengkel_servis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_mobil_id')->constrained('servis_mobil')->cascadeOnDelete();
            $table->foreignId('bengkel_id')->constrained('bengkel')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bengkel_servis');
    }
};
