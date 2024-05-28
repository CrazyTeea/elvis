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
        Schema::table('experiments', function (Blueprint $table) {
            $table->integer('br_min')->nullable();
            $table->integer('br_max')->nullable();
            $table->integer('x1')->nullable();
            $table->integer('x2')->nullable();
            $table->integer('y1')->nullable();
            $table->integer('y2')->nullable();
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
