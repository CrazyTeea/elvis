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
            $table->integer('x_h')->nullable();
            $table->integer('y_h')->nullable();
            $table->integer('x_v')->nullable();
            $table->integer('y_v')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('figures', function (Blueprint $table) {
            $table->dropColumn('x_y');
            $table->dropColumn('y_h');
            $table->dropColumn('x_v');
            $table->dropColumn('y_v');
        });
    }
};
