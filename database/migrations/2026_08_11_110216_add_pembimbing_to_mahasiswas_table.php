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
            $table->foreignId('pembimbing_1_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->foreignId('pembimbing_2_id')->nullable()->constrained('dosens')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropForeign(['pembimbing_1_id']);
            $table->dropForeign(['pembimbing_2_id']);
            $table->dropColumn(['pembimbing_1_id', 'pembimbing_2_id']);
        });
    }
};
