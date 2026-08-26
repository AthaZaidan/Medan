<?php

namespace Database\Seeders;

use App\Models\Dimensi;
use App\Models\Indikator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DimensiIndikatorBobotSeeder extends Seeder
{
    /**
     * Seed the dimensi_indikator_bobot pivot table.
     * Maps each dimension directly to its source indicators with weights.
     *
     * This is the CRITICAL table: dimension scores are calculated from indicator level,
     * NOT from sub-variable level.
     */
    public function run(): void
    {
        $dimensis = Dimensi::pluck('id', 'kode')->toArray();
        $now = now();

        // Pre-load all indikators with their sub-variabel and kuesioner
        $indikators = Indikator::with('subVariabel.kuesioner')->get();

        // Group by kuesioner kode for easy lookup
        $byKuesioner = $indikators->groupBy(fn ($i) => $i->subVariabel->kuesioner->kode);

        $pivotRows = [];

        // D1 = all 25 indikators from Kuesioner A, bobot 4 each
        foreach ($byKuesioner['A'] as $ind) {
            $pivotRows[] = [
                'dimensi_id' => $dimensis['D1'],
                'indikator_id' => $ind->id,
                'bobot' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // D2 = all 12 indikators from Kuesioner C, bobot 6.25 each
        foreach ($byKuesioner['C'] as $ind) {
            $pivotRows[] = [
                'dimensi_id' => $dimensis['D2'],
                'indikator_id' => $ind->id,
                'bobot' => 6.25,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // D3 = B1–B10 (Persampahan + Adminduk), with specific weights
        $d3Weights = [
            'B1' => 7, 'B2' => 7, 'B3' => 6, 'B4' => 7, 'B5' => 6,
            'B6' => 6, 'B7' => 7, 'B8' => 7, 'B9' => 6, 'B10' => 7,
        ];
        foreach ($byKuesioner['B'] as $ind) {
            if (isset($d3Weights[$ind->kode])) {
                $pivotRows[] = [
                    'dimensi_id' => $dimensis['D3'],
                    'indikator_id' => $ind->id,
                    'bobot' => $d3Weights[$ind->kode],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // D4 = B11(7), B12(7), B13(6) + all 10 Kuesioner D indikators (bobot 10 each)
        $d4BWeights = ['B11' => 7, 'B12' => 7, 'B13' => 6];
        foreach ($byKuesioner['B'] as $ind) {
            if (isset($d4BWeights[$ind->kode])) {
                $pivotRows[] = [
                    'dimensi_id' => $dimensis['D4'],
                    'indikator_id' => $ind->id,
                    'bobot' => $d4BWeights[$ind->kode],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        foreach ($byKuesioner['D'] as $ind) {
            $pivotRows[] = [
                'dimensi_id' => $dimensis['D4'],
                'indikator_id' => $ind->id,
                'bobot' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // D5 = B14(50), B15(50)
        $d5Weights = ['B14' => 50, 'B15' => 50];
        foreach ($byKuesioner['B'] as $ind) {
            if (isset($d5Weights[$ind->kode])) {
                $pivotRows[] = [
                    'dimensi_id' => $dimensis['D5'],
                    'indikator_id' => $ind->id,
                    'bobot' => $d5Weights[$ind->kode],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // D6 = all 20 indikators from Kuesioner E, with their bobot_asli as dimension weight
        foreach ($byKuesioner['E'] as $ind) {
            $pivotRows[] = [
                'dimensi_id' => $dimensis['D6'],
                'indikator_id' => $ind->id,
                'bobot' => $ind->bobot_asli,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // D7 = all 21 indikators from Kuesioner F, with their bobot_asli as dimension weight
        foreach ($byKuesioner['F'] as $ind) {
            $pivotRows[] = [
                'dimensi_id' => $dimensis['D7'],
                'indikator_id' => $ind->id,
                'bobot' => $ind->bobot_asli,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('dimensi_indikator_bobot')->insert($pivotRows);
    }
}
