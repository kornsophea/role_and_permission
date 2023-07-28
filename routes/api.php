<?php

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
Route::get('user/show-all',[UserController::class,'show']);
Route::post('user/login',[UserController::class,'login']);
Route::post('user/register',[UserController::class,'register']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('user/index',[UserController::class,'index']);
});
