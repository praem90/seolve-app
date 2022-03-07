<?php

use App\Http\Controllers\Auth\SanctumController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/token', [SanctumController::class, 'token']);
Route::post('/register', [SanctumController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('company', [CompanyController::class, 'index']);
    Route::post('company', [CompanyController::class, 'create']);
});
