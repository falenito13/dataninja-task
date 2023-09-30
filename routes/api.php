<?php

use App\Http\Controllers\AuthController;
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

Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class, 'register']);
Route::prefix('token')
    ->middleware('auth:token')
    ->controller(AuthController::class)
    ->name('auth.')
    ->group(function () {
        Route::post('/generate', 'store');
        Route::post('/delete/{userToken}', 'destroy');
    });
