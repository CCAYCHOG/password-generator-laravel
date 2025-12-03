<?php

use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PasswordController::class, 'index'])->name('password.index');
Route::post('/generate', [PasswordController::class, 'generate'])->name('password.generate');