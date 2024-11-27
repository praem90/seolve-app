<?php

use App\Http\Controllers\Auth\SanctumController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PostController;
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

Route::middleware('auth')->group(function () {
    Route::prefix('company')->group(function () {
        Route::get('/', [CompanyController::class, 'index']);
        Route::post('/', [CompanyController::class, 'create']);
        Route::get('{id}', [CompanyController::class, 'find']);

        Route::prefix('{company_id}/post')->group(function () {
            Route::post('/', [PostController::class, 'post']);
            Route::get('/', [PostController::class, 'index']);
        });
    });
});

Route::prefix('twitter')->name('twitter.')->group(function () {
    Route::get('redirect', \App\Http\Controllers\Twitter\TwitterAuthorizeController::class)->name('redirect');
    Route::get('callback', \App\Http\Controllers\Twitter\TwitterCallbackController::class)->name('callback');
});
