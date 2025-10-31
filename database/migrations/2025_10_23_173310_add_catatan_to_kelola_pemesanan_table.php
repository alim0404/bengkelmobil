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
        Schema::table('kelola_pemesanan', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('jam_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelola_pemesanan', function (Blueprint $table) {
            $table->dropColumn('catatan');
        });
    }
};