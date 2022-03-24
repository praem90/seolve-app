<?php

namespace App\Http\Libraries\SocialMedia\Drivers;

use App\Http\Libraries\SocialMedia\Contracts\SocialMediaInterface;
use App\Models\Company;
use App\Models\CompanyAccount;
use App\Models\Post;
use App\Models\PostAccount;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class Twitter implements SocialMediaInterface
{
	protected $scopes = [
		'offline.access',
		'tweet.read',
		'tweet.write',
		'users.read',
	];

	public const DRIVER = 'twitter';

    public function redirect()
    {
        return Socialite::driver(self::DRIVER . 'Oauth2')->setScopes($this->scopes)->enablePKCE()->redirect();
    }

    public function callback(Company $company)
    {
		$user = Socialite::driver(self::DRIVER . 'Oauth2')->enablePKCE()->user();

        $account = CompanyAccount::firstOrNew([
            'account_id' => $user->getId(),
            'company_id' => $company->id,
        ]);

        $account->medium = self::DRIVER;
        $account->name = $user->getName();
        $account->account_id = $user->getId();
        $account->access_token = $user->token;
        $account->logo = $user->getAvatar();
        $account->type = 'page';
        $account->meta = [
			'refresh_token' => $user->refreshToken
		];

		$company->accounts()->save($account);
    }

    public function post(Post $post, PostAccount $postAccount)
    {
        $data = [
            'text' => $post->message,
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $postAccount->account->access_token,
        ];

        $response = Http::withHeaders($headers)->post('https://api.twitter.com/2/tweets', $data);

		$postAccount->meta = $response->json();

		$postAccount->save();
    }

    public function uploadAsset()
    {
        // TODO: Implement uploadAsset() method.
    }
}
