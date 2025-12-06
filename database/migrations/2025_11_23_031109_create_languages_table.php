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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();

            $table->string('name');                // English, Bangla, Arabic
            $table->string('code', 10)->unique();  // en, bn, ar, etc.
            $table->string('flag')->nullable();    // flag image
            $table->boolean('is_default')->default(1)->comment('1 = default language');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
