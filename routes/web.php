<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('servers', ServerController::class);
    Route::post('servers/{server}/install-tool', [ServerController::class, 'installTool'])->name('servers.install-tool');
    
    Route::get('domains', [DomainController::class, 'index'])->name('domains.index');
    Route::post('servers/{server}/domains', [DomainController::class, 'store'])->name('domains.store');
    Route::patch('domains/{domain}', [DomainController::class, 'update'])->name('domains.update');
    Route::delete('domains/{domain}', [DomainController::class, 'destroy'])->name('domains.destroy');
    
    Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    
    Route::post('domains/{domain}/subdomains', [SubdomainController::class, 'store'])->name('subdomains.store');
    Route::patch('subdomains/{subdomain}', [SubdomainController::class, 'update'])->name('subdomains.update');
    Route::delete('subdomains/{subdomain}', [SubdomainController::class, 'destroy'])->name('subdomains.destroy');
});

require __DIR__.'/auth.php';
