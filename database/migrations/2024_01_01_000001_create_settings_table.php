<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Isi data default langsung di sini
        DB::table('settings')->insert([
            ['key' => 'logo_image', 'value' => null],
            ['key' => 'bg_image',   'value' => null],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};