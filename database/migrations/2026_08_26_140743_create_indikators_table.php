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
        Schema::create('indikators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_variabel_id')->constrained('sub_variabels')->cascadeOnDelete();
            $table->string('kode', 10);
            $table->text('pernyataan');
            $table->decimal('bobot_asli', 8, 2);
            $table->tinyInteger('urutan')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikators');
    }
};
