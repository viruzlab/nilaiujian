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
        Schema::table('dosens', function (Blueprint $table) {
            $table->boolean('first_login')->default(true)->after('password_plain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn('first_login');
        });
    }
};
