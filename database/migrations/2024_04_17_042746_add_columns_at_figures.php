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
        Schema::table('figures', function (Blueprint $table) {
            $table->integer('size_min')->nullable();
            $table->integer('size_max')->nullable();
            $table->integer('brightness_min')->nullable();
            $table->integer('brightness_max')->nullable();
            $table->integer('angle')->nullable();
            $table->integer('angles')->nullable();
            $table->text('colors')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
