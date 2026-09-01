<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KecamatanSeeder::class,
            KuesionerSeeder::class,
            DimensiSeeder::class,
            SubVariabelSeeder::class,
            IndikatorSeeder::class,
            SubItemSeeder::class,
            DimensiIndikatorBobotSeeder::class,
            ParameterSeeder::class,
            UserSeeder::class,
            JawabanSeeder::class,
        ]);
    }
}
