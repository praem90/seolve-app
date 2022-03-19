<?php

use App\Models\CompanyAccount;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;

Route::prefix('oauth')->group(function () {
    Route::get('{company_id}/{driver}/redirect', function ($company_id, $driver) {
        session()->flash('company_id', $company_id);
        $driver =  Socialite::driver($driver);
        if ($driver === 'facebook') {
            $driver->scopes(['pages_manage_posts']);
        }
        return $driver->redirect();
    })->where(['driver' => 'facebook|linkedin'])
        ->name('socialite.redirect');

    Route::get('{driver}/callback', function ($driver) {
        $user = Socialite::driver($driver)->user();
        $company_id = session('company_id');
        $company = auth()->user()->companies()->whereId($company_id)->first();

        abort_unless($company_id && $company, 404);

        if ($driver === 'facebook') {
            $url = 'https://graph.facebook.com/oauth/access_token';

            $response = Http::get($url, [
                'grant_type' => 'fb_exchange_token',
                'client_id' => config('services.facebook.client_id'),
                'client_secret' => config('services.facebook.client_secret'),
                'fb_exchange_token' => $user->token,
            ]);

            $access_token = $response->json('access_token');

            $url = 'https://graph.facebook.com/me/accounts';
            $response = Http::get($url, ['access_token' => $access_token]);

            $pages = $response->json('data');
        } else {
            $pages = [
                [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'access_token' => $user->token,
                    'avatar' => $user->getAvatar(),
                ]
            ];
        }
        $accounts = [];

        foreach ($pages as $page) {
            $account = CompanyAccount::firstOrNew([
                'account_id' => $page['id'],
                'company_id' => $company_id,
            ]);

            $account->medium = $driver;
            $account->name = $page['name'];
            $account->account_id = $page['id'];
            $account->access_token = $page['access_token'];
            $account->logo = $driver === 'facebook' ? url('images/icons/facebook.png') : $page['avatar'];
            $account->type = 'page';
            $account->meta = $page;

            $accounts[] = $account;
        }

        $company->accounts()->saveMany($accounts);

        return redirect()->route('dashboard');
    })->where(['driver' => 'facebook|linkedin'])
        ->name('socialite.callback');

    Route::get('{company_id}/twitter/redirect', \App\Http\Controllers\Twitter\TwitterAuthorizeController::class)->name('twitter.redirect');
    Route::get('twitter/callback', \App\Http\Controllers\Twitter\TwitterCallbackController::class)->name('twitter.callback')->withoutMiddleware('auth');
});
