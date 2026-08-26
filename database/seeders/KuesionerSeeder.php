<?php

namespace Database\Seeders;

use App\Models\Kuesioner;
use Illuminate\Database\Seeder;

class KuesionerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kuesioners = [
            ['kode' => 'A', 'nama' => 'Servqual – Kepuasan Masyarakat'],
            ['kode' => 'B', 'nama' => 'Kinerja Internal Aparatur (BSC)'],
            ['kode' => 'C', 'nama' => 'Tata Kelola & Good Governance'],
            ['kode' => 'D', 'nama' => 'Kematangan Digital / SPBE'],
            ['kode' => 'E', 'nama' => 'Modul Tematik PKH Makmur'],
            ['kode' => 'F', 'nama' => 'Modul Tematik Ketertiban Umum'],
        ];

        foreach ($kuesioners as $k) {
            Kuesioner::create($k);
        }
    }
}
