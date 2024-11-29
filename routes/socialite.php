<?php

use App\Http\Libraries\SocialMedia\Facade\SocialMedia;
use App\Models\CompanyAccount;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;

Route::prefix('oauth')->group(function () {
    Route::get('{company_id}/{driver}/redirect', function ($company_id, $driver) {
        session()->flash('company_id', $company_id);
        $driver =  SocialMedia::driver($driver);
        return $driver->redirect();
    })->where(['driver' => 'facebook|linkedin|twitter|linkedinOpenid'])
        ->name('socialite.redirect');

    Route::get('{driver}/callback', function ($driver) {
        $company = auth()->user()->companies()->whereId(session('company_id', 0))->first();

        abort_unless($company, 404);

        $driver =  SocialMedia::driver($driver);

        $driver->callback($company);

        return redirect()->route('dashboard');
    })->where(['driver' => 'facebook|linkedin|twitter|linkedinOpenid'])
        ->name('socialite.callback');

    /* Route::get('{company_id}/twitter/redirect', \App\Http\Controllers\Twitter\TwitterAuthorizeController::class)->name('twitter.redirect');
    Route::get('twitter/callback', \App\Http\Controllers\Twitter\TwitterCallbackController::class)->name('twitter.callback')->withoutMiddleware('auth'); */
});
