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
        $table->integer('rating')->nullable()->after('status_pembayaran'); // Rating dari 1-5
        $table->text('komentar')->nullable()->after('rating'); // Komentar dari customer
    });
}

public function down(): void
{
    Schema::table('kelola_pemesanan', function (Blueprint $table) {
        $table->dropColumn(['rating', 'komentar']);
    });
}
};