<?php

use App\Events\Example;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
