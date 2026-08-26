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
        Schema::create('sub_variabels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuesioner_id')->constrained('kuesioners')->cascadeOnDelete();
            $table->string('nama');
            $table->string('dimensi_kode', 5);
            $table->decimal('bobot_subtotal', 8, 2);
            $table->tinyInteger('urutan')->unsigned();
            $table->timestamps();

            $table->foreign('dimensi_kode')->references('kode')->on('dimensis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_variabels');
    }
};
