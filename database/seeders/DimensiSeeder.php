<?php

namespace Database\Seeders;

use App\Models\Dimensi;
use Illuminate\Database\Seeder;

class DimensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dimensis = [
            ['kode' => 'D1', 'nama' => 'Kepuasan Masyarakat', 'urutan' => 1],
            ['kode' => 'D2', 'nama' => 'Tata Kelola', 'urutan' => 2],
            ['kode' => 'D3', 'nama' => 'Proses Bisnis Internal', 'urutan' => 3],
            ['kode' => 'D4', 'nama' => 'Kematangan Digital', 'urutan' => 4],
            ['kode' => 'D5', 'nama' => 'Anggaran & Efisiensi', 'urutan' => 5],
            ['kode' => 'D6', 'nama' => 'PKH Makmur (Tematik)', 'urutan' => 6],
            ['kode' => 'D7', 'nama' => 'Ketertiban Umum (Tematik)', 'urutan' => 7],
        ];

        foreach ($dimensis as $d) {
            Dimensi::create($d);
        }
    }
}
