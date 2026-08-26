<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatans = [
            'Medan Tembung',
            'Medan Belawan',
            'Medan Marelan',
            'Medan Amplas',
            'Medan Perjuangan',
            'Medan Deli',
            'Medan Tuntungan',
            'Medan Selayang',
            'Medan Polonia',
            'Medan Area',
            'Medan Kota',
            'Medan Baru',
            'Medan Johor',
            'Medan Denai',
            'Medan Labuhan',
            'Medan Timur',
            'Medan Barat',
            'Medan Helvetia',
            'Medan Sunggal',
            'Medan Maimun',
            'Medan Petisah',
        ];

        foreach ($kecamatans as $index => $nama) {
            Kecamatan::create([
                'nama' => $nama,
                'urutan' => $index + 1,
            ]);
        }
    }
}
