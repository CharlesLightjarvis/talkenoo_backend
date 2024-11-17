<?php


use App\Http\Controllers\API\ContenuSectionController;
use App\Http\Controllers\API\CoursController;
use App\Http\Controllers\API\NiveauController;
use App\Http\Controllers\API\SectionCoursController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\OtpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/users', UserController::class);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);

// Routes pour les niveaux
Route::apiResource('niveaux', NiveauController::class);
Route::post('niveaux/reorder', [NiveauController::class, 'reorder']);

// Routes pour les cours
Route::apiResource('cours', CoursController::class);
Route::post('cours/reorder', [CoursController::class, 'reorder']);

// Routes pour les sections
Route::apiResource('sections', SectionCoursController::class);
Route::post('sections/reorder', [SectionCoursController::class, 'reorder']);

// Routes pour les contenus
Route::apiResource('contenus', ContenuSectionController::class);
Route::post('contenus/reorder', [ContenuSectionController::class, 'reorder']);
