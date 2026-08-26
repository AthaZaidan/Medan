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
        Schema::create('dimensi_indikator_bobot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dimensi_id')->constrained('dimensis')->cascadeOnDelete();
            $table->foreignId('indikator_id')->constrained('indikators')->cascadeOnDelete();
            $table->decimal('bobot', 8, 2);
            $table->timestamps();

            $table->unique(['dimensi_id', 'indikator_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dimensi_indikator_bobot');
    }
};
