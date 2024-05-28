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
        Schema::table('figures', function (Blueprint $table): void {
            $table->string('xx')->nullable();
            $table->string('yy')->nullable();
        });
        Schema::table('figure_results', function (Blueprint $table): void {
            $table->string('x')->nullable();
            $table->string('y')->nullable();
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
