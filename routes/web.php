<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? to_route('dashboard.index') : to_route('login');
})->name('home');

Route::group(['middleware' => 'guest'], function () {
    Route::group(['controller' => LoginController::class], function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'store')->name('login.store');
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});
