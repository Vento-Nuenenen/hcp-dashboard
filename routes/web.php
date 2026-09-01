<?php

use App\Http\Controllers\ServerController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\SubdomainController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('servers.index');
});

Route::resource('servers', ServerController::class);
Route::post('servers/{server}/domains', [DomainController::class, 'store'])->name('domains.store');
Route::post('domains/{domain}/subdomains', [SubdomainController::class, 'store'])->name('subdomains.store');
