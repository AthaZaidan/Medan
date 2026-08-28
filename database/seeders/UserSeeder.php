<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin (Akses Penuh ke Semua Fitur & Area)
        User::updateOrCreate(
            ['email' => 'admin@medan.go.id'],
            [
                'name' => 'Administrator Pemko Medan',
                'password' => 'password',
                'role' => 'admin',
                'kecamatan_id' => null,
            ]
        );

        // 2. Sample User Area Operator Kecamatan
        $tembung = Kecamatan::where('nama', 'LIKE', '%Tembung%')->first();
        if ($tembung) {
            User::updateOrCreate(
                ['email' => 'tembung@medan.go.id'],
                [
                    'name' => 'Operator '.$tembung->nama,
                    'password' => 'password',
                    'role' => 'user_area',
                    'kecamatan_id' => $tembung->id,
                ]
            );
        }

        $amplas = Kecamatan::where('nama', 'LIKE', '%Amplas%')->first();
        if ($amplas) {
            User::updateOrCreate(
                ['email' => 'amplas@medan.go.id'],
                [
                    'name' => 'Operator '.$amplas->nama,
                    'password' => 'password',
                    'role' => 'user_area',
                    'kecamatan_id' => $amplas->id,
                ]
            );
        }
    }
}
