<?php

use App\Models\CompanyAccount;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	$post = Post::find(22);

    $dats = \App\Http\Libraries\SocialMedia\Facade\SocialMedia::driver('linkedin')->post($post, $post->postAccounts->first());
	dd($dats->meta);
	$page = CompanyAccount::where('medium', 'facebook')->first();
	$page = [
		'id' => $page['account_id'],
		'access_token' => $page['access_token']
	];
    return \App\Http\Libraries\SocialMedia\Facade\SocialMedia::driver('facebook')->exchangeToken($page['access_token']);
});

Route::get('twitter', \App\Http\Controllers\Twitter\TwitterAuthorizeController::class);
Route::get('callback', \App\Http\Controllers\Twitter\TwitterCallbackController::class);

Route::get('instagram', [\App\Http\Controllers\Instagram\InstagramAuthController::class, 'redirectToProvider']);
Route::get('instagram/callback', [\App\Http\Controllers\Instagram\InstagramAuthController::class, 'callback']);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('company/{id}', function () {
        return view('company');
    })->name('company.dashboard');

    require __DIR__ . '/socialite.php';
});

require __DIR__ . '/auth.php';
