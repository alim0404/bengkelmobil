<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelola_pemesanan', function (Blueprint $table) {
            $table->foreignId('servis_variant_id')->nullable()->after('servis_mobil_id')
                ->constrained('servis_variants')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kelola_pemesanan', function (Blueprint $table) {
            $table->dropForeign(['servis_variant_id']);
            $table->dropColumn('servis_variant_id');
        });
    }
};