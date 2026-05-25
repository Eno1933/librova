<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Data default
        DB::table('settings')->insert([
            ['key' => 'site_name',        'value' => 'Librova'],
            ['key' => 'site_description', 'value' => 'Perpustakaan digital untuk semua.'],
            ['key' => 'logo_url',         'value' => null],
            ['key' => 'favicon_url',      'value' => null],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};