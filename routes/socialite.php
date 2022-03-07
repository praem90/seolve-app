<?php

use Laravel\Socialite\Facades\Socialite;

Route::prefix('oauth')->group(function () {
    Route::get('{driver}/redirect', function ($driver) {
        return Socialite::driver($driver)
            ->scopes(['pages_manage_posts'])
            ->redirect();
    })->where(['driver' => 'facebook'])
        ->name('socialite.redirect');

    Route::get('{driver}/callback', function ($driver) {
        $user = Socialite::driver($driver)->user();
        dd($user);
    })->where(['driver' => 'facebook'])
        ->name('socialite.callback');
});
