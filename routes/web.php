<?php

use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/control-center', [LandingPageController::class, 'controlCenter'])->name('control-center');
Route::get('/client-hub', [LandingPageController::class, 'clientHub'])->name('client-hub');
