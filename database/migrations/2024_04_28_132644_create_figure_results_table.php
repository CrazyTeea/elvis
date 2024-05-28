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
        Schema::create('figure_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('figure_id')->constrained('figures');
            $table->foreignId('experiment_id')->constrained('experiments');
            $table->float('reaction_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('figure_results');
    }
};
