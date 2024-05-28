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
            $table->integer('x_oblast')->nullable();
            $table->integer('y_oblast')->nullable();
            $table->integer('b')->nullable();
        });
        Schema::table('figures', function (Blueprint $table) {
            $table->integer('x_oblast')->nullable();
            $table->integer('y_oblast')->nullable();
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
