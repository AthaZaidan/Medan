<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Truncate semua data jawaban lama, lalu tambah kolom periode.
     * Di MySQL, FK constraint perlu di-drop dulu sebelum drop unique index.
     */
    public function up(): void
    {
        // Hapus semua data jawaban lama (mulai dari awal dengan periode)
        DB::table('jawabans')->truncate();

        Schema::table('jawabans', function (Blueprint $table) {
            $isSqlite = DB::getDriverName() === 'sqlite';

            // Drop FK constraints dulu (MySQL butuh ini sebelum drop unique)
            if (! $isSqlite) {
                $table->dropForeign('jawabans_sub_item_id_foreign');
                $table->dropForeign('jawabans_kecamatan_id_foreign');
                $table->dropForeign('jawabans_updated_by_foreign');
            }

            // Drop unique constraint lama
            $table->dropUnique(['sub_item_id', 'kecamatan_id']);

            // Tambah kolom periode
            $table->tinyInteger('periode_bulan')->unsigned()->nullable()->after('kecamatan_id'); // 1-12
            $table->smallInteger('periode_tahun')->unsigned()->nullable()->after('periode_bulan'); // misal 2026

            // Unique constraint baru: satu jawaban per sub-item + kecamatan + periode
            $table->unique(['sub_item_id', 'kecamatan_id', 'periode_bulan', 'periode_tahun'], 'jawabans_periode_unique');

            // Index untuk filter periode
            $table->index(['periode_bulan', 'periode_tahun'], 'jawabans_periode_index');

            // Re-create FK constraints
            if (! $isSqlite) {
                $table->foreign('sub_item_id')->references('id')->on('sub_items')->cascadeOnDelete();
                $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->cascadeOnDelete();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('jawabans')->truncate();

        Schema::table('jawabans', function (Blueprint $table) {
            $isSqlite = DB::getDriverName() === 'sqlite';

            if (! $isSqlite) {
                $table->dropForeign('jawabans_sub_item_id_foreign');
                $table->dropForeign('jawabans_kecamatan_id_foreign');
                $table->dropForeign('jawabans_updated_by_foreign');
            }

            $table->dropUnique('jawabans_periode_unique');
            $table->dropIndex('jawabans_periode_index');
            $table->dropColumn(['periode_bulan', 'periode_tahun']);

            $table->unique(['sub_item_id', 'kecamatan_id']);

            if (! $isSqlite) {
                $table->foreign('sub_item_id')->references('id')->on('sub_items')->cascadeOnDelete();
                $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->cascadeOnDelete();
                $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }
};
