<?php

use App\Models\Indikator;
use App\Models\Jawaban;
use App\Models\Kecamatan;
use App\Models\Kuesioner;
use App\Models\SubItem;
use App\Services\QpiCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    $this->service = app(QpiCalculationService::class);
});

test('skor indikator calculates correctly according to Guttman formula', function () {
    $kecamatan = Kecamatan::first();
    $indikator = Indikator::first();
    $subItems = SubItem::where('indikator_id', $indikator->id)->get();

    // 0 items filled -> null
    expect($this->service->skorIndikator($indikator->id, $kecamatan->id))->toBeNull();

    // Fill 3 of 5 sub-items as Ya (1)
    foreach ($subItems->take(3) as $item) {
        Jawaban::create([
            'sub_item_id' => $item->id,
            'kecamatan_id' => $kecamatan->id,
            'nilai' => true,
        ]);
    }

    // 3 / 5 * 100 = 60.0
    expect($this->service->skorIndikator($indikator->id, $kecamatan->id))->toBe(60.0);
});

test('skor dimensi aggregates indicator level directly with weights', function () {
    $kecamatan = Kecamatan::first();

    // Seed answers for all indicators of Kuesioner A (D1)
    $kuesionerA = Kuesioner::where('kode', 'A')->first();
    $indikators = $kuesionerA->indikators;

    foreach ($indikators as $ind) {
        $subItems = SubItem::where('indikator_id', $ind->id)->get();
        // Set 4 out of 5 sub-items to Ya -> score 80 for each indicator
        foreach ($subItems->take(4) as $item) {
            Jawaban::create([
                'sub_item_id' => $item->id,
                'kecamatan_id' => $kecamatan->id,
                'nilai' => true,
            ]);
        }
    }

    // Dimension D1 score should be 80.0
    expect($this->service->skorDimensi('D1', $kecamatan->id))->toBe(80.0);
});

test('floor status becomes active when a core dimension score is below 50', function () {
    $kecamatan = Kecamatan::first();

    // Fill all 5 core dimensions D1-D5
    foreach (['A', 'B', 'C', 'D'] as $kuesKode) {
        $kuesioner = Kuesioner::where('kode', $kuesKode)->first();
        foreach ($kuesioner->indikators as $ind) {
            $subItems = SubItem::where('indikator_id', $ind->id)->get();
            // Default 4 out of 5 Ya -> score 80
            foreach ($subItems->take(4) as $item) {
                Jawaban::create([
                    'sub_item_id' => $item->id,
                    'kecamatan_id' => $kecamatan->id,
                    'nilai' => true,
                ]);
            }
        }
    }

    // Now all D1-D5 are 80 -> floor should be TIDAK_AKTIF
    expect($this->service->floorStatus($kecamatan->id))->toBe('TIDAK_AKTIF');
    expect($this->service->kategori($kecamatan->id)['kategori'])->toBe('Baik');
});

test('dashboard routes respond successfully', function () {
    $kecamatan = Kecamatan::first();
    $kuesioner = Kuesioner::first();

    $this->get(route('dashboard'))->assertOk();
    $this->get(route('kecamatan.detail', $kecamatan))->assertOk();
    $this->get(route('input.progress'))->assertOk();
    $this->get(route('input.show', [$kuesioner, $kecamatan]))->assertOk();
    $this->get(route('parameter.index'))->assertOk();
    $this->get(route('panduan.index'))->assertOk();
});

test('checklist submission saves answers to database', function () {
    $kecamatan = Kecamatan::first();
    $kuesioner = Kuesioner::first();
    $subItem = SubItem::first();

    $response = $this->post(route('input.store'), [
        'kecamatan_id' => $kecamatan->id,
        'kuesioner_id' => $kuesioner->id,
        'jawaban' => [
            $subItem->id => '1',
        ],
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('jawabans', [
        'sub_item_id' => $subItem->id,
        'kecamatan_id' => $kecamatan->id,
        'nilai' => true,
    ]);
});
