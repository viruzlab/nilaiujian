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
        Schema::table('nilai_sidangs', function (Blueprint $table) {
            $table->integer('urutan')->nullable()->after('dosen_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_sidangs', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
};
