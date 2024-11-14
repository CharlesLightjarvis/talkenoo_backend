<?php


use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\OtpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('/users', UserController::class);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);
