<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return empty(session('data'))
        ? to_route('login')
        : to_route('dashboard.index');
});

Route::group(['controller' => LoginController::class], function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'store')->name('login.store');
});
