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
        Schema::table('users', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik')->nullable()->unique()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'nim')) {
                $table->string('nim')->nullable()->unique()->after('nik');
            }
            
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('mahasiswa')->after('password');
            }
            
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nik')) {
                $table->dropColumn('nik');
            }
            
            if (Schema::hasColumn('users', 'nim')) {
                $table->dropColumn('nim');
            }
            
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
        });
    }
};
