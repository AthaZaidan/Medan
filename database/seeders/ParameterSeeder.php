<?php

namespace Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Seeder;

class ParameterSeeder extends Seeder
{
    /**
     * Seed the editable parameters (dimension weights, category thresholds, floor).
     */
    public function run(): void
    {
        $parameters = [
            // Bobot Dimensi D1-D5 for QPI Inti (must sum to 100%)
            ['key' => 'bobot_d1', 'label' => 'Bobot D1 – Kepuasan Masyarakat', 'group' => 'bobot_dimensi', 'value' => 26.19],
            ['key' => 'bobot_d2', 'label' => 'Bobot D2 – Tata Kelola', 'group' => 'bobot_dimensi', 'value' => 14.29],
            ['key' => 'bobot_d3', 'label' => 'Bobot D3 – Proses Bisnis Internal', 'group' => 'bobot_dimensi', 'value' => 26.19],
            ['key' => 'bobot_d4', 'label' => 'Bobot D4 – Kematangan Digital', 'group' => 'bobot_dimensi', 'value' => 26.19],
            ['key' => 'bobot_d5', 'label' => 'Bobot D5 – Anggaran & Efisiensi', 'group' => 'bobot_dimensi', 'value' => 7.14],

            // Ambang kategori (batas bawah masing-masing)
            ['key' => 'ambang_sangat_baik', 'label' => 'Ambang Sangat Baik', 'group' => 'ambang_kategori', 'value' => 85],
            ['key' => 'ambang_baik', 'label' => 'Ambang Baik', 'group' => 'ambang_kategori', 'value' => 75],
            ['key' => 'ambang_cukup', 'label' => 'Ambang Cukup', 'group' => 'ambang_kategori', 'value' => 65],
            ['key' => 'ambang_perlu_perbaikan', 'label' => 'Ambang Perlu Perbaikan', 'group' => 'ambang_kategori', 'value' => 50],
            ['key' => 'ambang_kritis', 'label' => 'Ambang Kritis', 'group' => 'ambang_kategori', 'value' => 0],

            // Floor threshold
            ['key' => 'ambang_floor', 'label' => 'Ambang Floor (Non-Kompensatori)', 'group' => 'floor', 'value' => 50],
        ];

        foreach ($parameters as $param) {
            Parameter::create($param);
        }
    }
}
