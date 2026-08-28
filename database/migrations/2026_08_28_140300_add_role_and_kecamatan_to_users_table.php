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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user_area')->after('email'); // 'admin' or 'user_area'
            $table->foreignId('kecamatan_id')->nullable()->after('role')->constrained('kecamatans')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['kecamatan_id']);
            }
            $table->dropColumn(['role', 'kecamatan_id']);
        });
    }
};
