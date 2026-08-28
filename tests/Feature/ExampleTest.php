<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated users are redirected to login', function () {
    $this->seed();

    $response = $this->get('/');

    $response->assertRedirect('/login');
});

test('authenticated users can access the application', function () {
    $this->seed();
    $admin = User::where('role', 'admin')->first();

    $response = $this->actingAs($admin)->get('/');

    $response->assertStatus(200);
});
