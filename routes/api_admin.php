<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::resource('/posts', PostController::class);
});
