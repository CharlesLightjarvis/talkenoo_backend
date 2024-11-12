<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('/users', UserController::class);
Route::post('/login', LoginController::class);

Route::post('/send-message', [MessageController::class, 'sendMessage']);
Route::get('/messages', [MessageController::class, 'index']);
