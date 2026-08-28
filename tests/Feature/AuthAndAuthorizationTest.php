<?php

use App\Models\Kecamatan;
use App\Models\Kuesioner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();

    $this->admin = User::where('role', 'admin')->first();
    $this->userTembung = User::where('email', 'tembung@medan.go.id')->first();
    $this->userAmplas = User::where('email', 'amplas@medan.go.id')->first();
});

test('unauthenticated users are redirected to login page', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
    $this->get(route('input.progress'))->assertRedirect(route('login'));
    $this->get(route('admin.users.index'))->assertRedirect(route('login'));
});

test('admin can log in successfully and access admin control', function () {
    $response = $this->post(route('login.post'), [
        'email' => 'admin@medan.go.id',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($this->admin);

    // Can access admin control
    $this->actingAs($this->admin)
        ->get(route('admin.users.index'))
        ->assertOk();
});

test('operator area user cannot access admin control', function () {
    $this->actingAs($this->userTembung)
        ->get(route('admin.users.index'))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('error');
});

test('operator area user can only access input for their own kecamatan', function () {
    $kuesioner = Kuesioner::first();
    $kecamatanTembung = $this->userTembung->kecamatan;
    $kecamatanAmplas = $this->userAmplas->kecamatan;

    // Accessing own kecamatan input form -> 200 OK
    $this->actingAs($this->userTembung)
        ->get(route('input.show', [$kuesioner, $kecamatanTembung, 'bulan' => 8, 'tahun' => 2026]))
        ->assertOk();

    // Accessing another kecamatan input form -> Redirected with error
    $this->actingAs($this->userTembung)
        ->get(route('input.show', [$kuesioner, $kecamatanAmplas, 'bulan' => 8, 'tahun' => 2026]))
        ->assertRedirect(route('input.progress', ['bulan' => 8, 'tahun' => 2026]))
        ->assertSessionHas('error');
});

test('admin can access input form for any kecamatan', function () {
    $kuesioner = Kuesioner::first();
    $kecamatan = Kecamatan::first();

    $this->actingAs($this->admin)
        ->get(route('input.show', [$kuesioner, $kecamatan, 'bulan' => 8, 'tahun' => 2026]))
        ->assertOk();
});

test('admin can create new user area account via admin control', function () {
    $kecamatan = Kecamatan::first();

    $response = $this->actingAs($this->admin)
        ->post(route('admin.users.store'), [
            'name' => 'Operator Baru',
            'email' => 'operator.baru@medan.go.id',
            'password' => 'password123',
            'role' => 'user_area',
            'kecamatan_id' => $kecamatan->id,
        ]);

    $response->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseHas('users', [
        'email' => 'operator.baru@medan.go.id',
        'role' => 'user_area',
        'kecamatan_id' => $kecamatan->id,
    ]);
});
