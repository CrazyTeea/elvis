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
        Schema::table('figure_results', function (Blueprint $table) {
            $table->integer('h')->nullable();
            $table->integer('w')->nullable();
        });
        Schema::table('figures', function (Blueprint $table) {
            $table->string('hh')->nullable();
            $table->string('ww')->nullable();
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
