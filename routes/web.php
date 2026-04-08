<?php

use Illuminate\Support\Facades\Route;

$domain = parse_url(config('app.url'), PHP_URL_HOST);

// Rute untuk Subdomain (Tenant)
Route::domain('{tenant:slug}.' . $domain)->group(function () {
    Route::get('/', function (\App\Models\Tenant $tenant) {
        return view('tenant.landing', ['tenant' => $tenant]);
    })->name('tenant.landing');
});

// Rute untuk Domain Utama (Central)
Route::domain($domain)->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('central.landing');
});
