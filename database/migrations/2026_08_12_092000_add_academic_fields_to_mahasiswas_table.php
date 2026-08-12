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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->decimal('jumlah_mutu', 8, 2)->nullable()->after('pembimbing_2_id');
            $table->integer('jumlah_sks')->nullable()->after('jumlah_mutu');
            $table->integer('mata_kuliah_ulang')->nullable()->default(0)->after('jumlah_sks');
            $table->string('semester')->nullable()->after('mata_kuliah_ulang');
            $table->decimal('ipk', 3, 2)->nullable()->after('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['jumlah_mutu', 'jumlah_sks', 'mata_kuliah_ulang', 'semester', 'ipk']);
        });
    }
};
