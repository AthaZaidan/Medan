<?php

namespace Database\Seeders;

use App\Models\Jawaban;
use App\Models\Kecamatan;
use App\Models\SubItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class JawabanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing answers first to prevent duplicates
        Jawaban::truncate();

        $kecamatans = Kecamatan::orderBy('urutan')->get();
        $subItems = SubItem::with('indikator')->orderBy('id')->get();
        $adminUser = User::where('role', 'admin')->first();
        $adminId = $adminUser ? $adminUser->id : null;

        // Base quality probabilities per kecamatan ID (1 to 21)
        // Range from 0.38 (lower/kritis/floor) to 0.94 (sangat baik)
        $qualityFactors = [
            1 => 0.82, // Medan Tembung - Baik
            2 => 0.44, // Medan Belawan - Kritis / Floor
            3 => 0.62, // Medan Marelan - Cukup
            4 => 0.78, // Medan Amplas - Baik
            5 => 0.88, // Medan Perjuangan - Sangat Baik
            6 => 0.38, // Medan Deli - Kritis
            7 => 0.75, // Medan Tuntungan - Baik
            8 => 0.84, // Medan Selayang - Baik
            9 => 0.91, // Medan Polonia - Sangat Baik
            10 => 0.70, // Medan Area - Cukup
            11 => 0.89, // Medan Kota - Sangat Baik
            12 => 0.94, // Medan Baru - Sangat Baik
            13 => 0.80, // Medan Johor - Baik
            14 => 0.68, // Medan Denai - Cukup
            15 => 0.52, // Medan Labuhan - Perlu Perbaikan
            16 => 0.76, // Medan Timur - Baik
            17 => 0.92, // Medan Barat - Sangat Baik
            18 => 0.73, // Medan Helvetia - Cukup
            19 => 0.81, // Medan Sunggal - Baik
            20 => 0.77, // Medan Maimun - Baik
            21 => 0.87, // Medan Petisah - Sangat Baik
        ];

        // Seed 3 periods: June, July, August 2026
        $periodes = [
            ['bulan' => 6, 'tahun' => 2026],
            ['bulan' => 7, 'tahun' => 2026],
            ['bulan' => 8, 'tahun' => 2026],
        ];

        $now = now();
        $batch = [];
        $batchSize = 1000;

        foreach ($periodes as $periode) {
            $bulan = $periode['bulan'];
            $tahun = $periode['tahun'];

            foreach ($kecamatans as $kec) {
                $baseProb = $qualityFactors[$kec->id] ?? 0.75;

                // Slight variation across periods
                $periodBonus = ($bulan - 6) * 0.02; // gradual improvement over time

                foreach ($subItems as $subItem) {
                    $indId = $subItem->indikator_id;

                    // Apply specific indicator variations to create realistic weak spots (Top 10 Indikator Termurah)
                    $indPenalty = 0.0;
                    if (in_array($indId % 17, [3, 7, 11])) {
                        $indPenalty = 0.25; // Certain indicators are consistently harder across the city
                    }

                    $finalProb = max(0.10, min(0.98, $baseProb + $periodBonus - $indPenalty));

                    // Deterministic pseudo-randomness based on IDs & period for reproducibility
                    $seed = ($kec->id * 10000) + ($subItem->id * 10) + $bulan;
                    mt_srand($seed);
                    $nilai = (mt_rand(1, 100) <= ($finalProb * 100));

                    $batch[] = [
                        'sub_item_id' => $subItem->id,
                        'kecamatan_id' => $kec->id,
                        'periode_bulan' => $bulan,
                        'periode_tahun' => $tahun,
                        'nilai' => $nilai,
                        'updated_by' => $adminId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    if (count($batch) >= $batchSize) {
                        Jawaban::insert($batch);
                        $batch = [];
                    }
                }
            }
        }

        if (! empty($batch)) {
            Jawaban::insert($batch);
        }

        // Reset random generator seed
        mt_srand();
    }
}
