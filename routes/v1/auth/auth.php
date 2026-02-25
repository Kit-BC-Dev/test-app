<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Auth\RegistrationController;


Route::post('login', [AuthController::class, 'login'])->name('user.login');
Route::post('register', RegistrationController::class)->name('user.registration');