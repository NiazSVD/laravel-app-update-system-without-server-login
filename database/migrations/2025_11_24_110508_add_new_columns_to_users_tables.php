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
            $table->unsignedBigInteger('team_id')->after('password')->nullable();
            $table->string('employee_code')->after('team_id')->nullable()->unique();
            $table->boolean('status')->after('employee_code')->default(1)->comment('0 = inactive, 1 = active');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
