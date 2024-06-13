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
        Schema::create('exp2_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experiment_id')->constrained('experiments');
            $table->foreignId('stimul_id')->nullable()->constrained('stimuls');
            $table->foreignId('helper_id')->nullable()->constrained('helpers');
            $table->foreignId('position_id')->nullable()->constrained('positions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exp2_results');
    }
};
