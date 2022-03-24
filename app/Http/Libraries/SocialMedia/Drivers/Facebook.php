<?php

namespace App\Http\Libraries\SocialMedia\Drivers;

use App\Http\Libraries\SocialMedia\Contracts\SocialMediaInterface;
use App\Models\Company;
use App\Models\CompanyAccount;
use App\Models\Post;
use App\Models\PostAccount;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class Facebook implements SocialMediaInterface
{
	const DRIVER = 'facebook';
    public function redirect()
    {
        $driver =  Socialite::driver(self::DRIVER);
        $driver->scopes(['pages_manage_posts']);

        return $driver->redirect();
    }

    public function callback(Company $company)
    {
        $user = Socialite::driver(self::DRIVER)->user();

        $access_token = $this->exchangeToken($user->token);

        $url = 'https://graph.facebook.com/me/accounts';
        $response = Http::get($url, ['access_token' => $access_token]);

        $pages = $response->json('data');

        $accounts = [];

        foreach ($pages as $page) {
            $account = CompanyAccount::firstOrNew([
                'account_id' => $page['id'],
                'company_id' => $company->id,
            ]);

            $account->medium = self::DRIVER;
            $account->name = $page['name'];
            $account->account_id = $page['id'];
            $account->access_token = $page['access_token'];
            $account->logo = $this->getProfilePicture($page) ?? url('images/icons/facebook.png');
            $account->type = 'page';
            $account->meta = $page;

            $accounts[] = $account;
        }

        $company->accounts()->saveMany($accounts);
    }

	public function exchangeToken($token)
	{
        $url = 'https://graph.facebook.com/oauth/access_token';

        $response = Http::get($url, [
            'grant_type' => 'fb_exchange_token',
            'client_id' => config('services.facebook.client_id'),
            'client_secret' => config('services.facebook.client_secret'),
            'fb_exchange_token' => $token,
        ]);

		if ($response->successful()) {
        	return $response->json('access_token');
		}

		return false;
	}

	public function getPages($access_token)
	{
        $url = 'https://graph.facebook.com/me/accounts';
        $response = Http::get($url, ['access_token' => $access_token]);

		if ($response->successful()) {
        	return $response->json('data');
		}

		return [];
	}

	public function getProfilePicture($page)
	{
        $url = 'https://graph.facebook.com/' . $page['id'] . '/picture';
        $response = Http::get($url, ['access_token' => $page['access_token'], 'redirect' => 0]);

		if ($response->successful()) {
        	$info = $response->json('data');

			return $info['url'];
		}

		return false;
	}

    public function post(Post $post, PostAccount $postAccount)
    {
        $data = [
            'message' => $post->message,
            'access_token' => $postAccount->account->access_token,
        ];

        if (app()->environment('local')) {
            $data['published'] = 'false';
        }

        $response = Http::post('https://graph.facebook.com/' . $postAccount->account->account_id . '/feed', $data);

		$postAccount->meta = $response->json();

		$postAccount->save();
    }

    public function uploadAsset()
    {
        // TODO: Implement uploadAsset() method.
    }
}
