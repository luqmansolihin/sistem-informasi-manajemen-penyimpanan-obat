<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TransactionMedicineController;
use App\Http\Controllers\TransactionPatientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? to_route('dashboard.index') : to_route('login');
})->name('home');

Route::group(['middleware' => 'guest'], function () {
    Route::group(['controller' => LoginController::class], function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'store')->name('login.store');
    });

    Route::group(['controller' => AuthController::class], function () {
        Route::get('/forgot-password', 'forgotPassword')->name('password.request');
        Route::post('/forgot-password', 'sendResetPasswordEmail')->name('password.email');
        Route::get('/reset-password/{token}', 'passwordResetToken')->name('password.reset');
        Route::post('/reset-password', 'resetPassword')->name('password.update');
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::group(['prefix' => 'medicines'], function () {
        Route::get('/', [MedicineController::class, 'index'])->name('medicines.index');
        Route::delete('/{id}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
        Route::get('/create', [MedicineController::class, 'create'])->name('medicines.create');
        Route::post('/store', [MedicineController::class, 'store'])->name('medicines.store');
        Route::get('/{id}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
        Route::put('/{id}/update', [MedicineController::class, 'update'])->name('medicines.update');
    });

    Route::group(['prefix' => 'patients'], function () {
        Route::get('/', [PatientController::class, 'index'])->name('patients.index');
        Route::delete('/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');
        Route::get('/create', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/store', [PatientController::class, 'store'])->name('patients.store');
        Route::get('/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
        Route::put('/{id}/update', [PatientController::class, 'update'])->name('patients.update');
    });

    Route::group(['prefix' => 'transactions'], function () {
        Route::group(['prefix' => 'medicines'], function () {
            Route::get('/', [TransactionMedicineController::class, 'index'])->name('transactions.medicines.index');
            Route::delete('/{id}', [TransactionMedicineController::class, 'destroy'])->name('transactions.medicines.destroy');
            Route::get('/create', [TransactionMedicineController::class, 'create'])->name('transactions.medicines.create');
            Route::post('/store', [TransactionMedicineController::class, 'store'])->name('transactions.medicines.store');
            Route::get('/{id}/edit', [TransactionMedicineController::class, 'edit'])->name('transactions.medicines.edit');
            Route::put('/{id}/update', [TransactionMedicineController::class, 'update'])->name('transactions.medicines.update');
        });

        Route::group(['prefix' => 'patients'], function () {
            Route::get('/', [TransactionPatientController::class, 'index'])->name('transactions.patients.index');
            Route::delete('/{id}', [TransactionPatientController::class, 'destroy'])->name('transactions.patients.destroy');
            Route::get('/create', [TransactionPatientController::class, 'create'])->name('transactions.patients.create');
            Route::get('/{id}', [TransactionPatientController::class, 'show'])->name('transactions.patients.show');
            Route::get('/{id}/delete', [TransactionPatientController::class, 'show'])->name('transactions.patients.preview.delete');
            Route::post('/store', [TransactionPatientController::class, 'store'])->name('transactions.patients.store');
            Route::get('/{id}/edit', [TransactionPatientController::class, 'edit'])->name('transactions.patients.edit');
            Route::put('/{id}/update', [TransactionPatientController::class, 'update'])->name('transactions.patients.update');
        });
    });
});
