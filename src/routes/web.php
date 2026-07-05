<?php

use App\Http\Controllers\Auth\UniversalLoginController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/
Route::get('/', [UniversalLoginController::class, 'show'])->name('login');
Route::post('/login', [UniversalLoginController::class, 'store'])->middleware('guest')->name('login.store');
Route::post('/logout', [UniversalLoginController::class, 'destroy'])->middleware('auth')->name('logout');
