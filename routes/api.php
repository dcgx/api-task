<?php

use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

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

Route::post('login', [LoginController::class, 'login']);;
Route::post('register', [RegisteredUserController::class, 'store']);;


Route::middleware('auth:sanctum')->group(function () {
    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store']);;

    Route::apiResource('tasks', TaskController::class);
});
