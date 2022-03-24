<?php

namespace App\Http\Libraries\SocialMedia\Drivers;

use App\Http\Libraries\SocialMedia\Contracts\SocialMediaInterface;
use App\Models\Company;
use App\Models\CompanyAccount;
use App\Models\Post;
use App\Models\PostAccount;
use ArrayObject;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class LinkedIn implements SocialMediaInterface
{
	public $scopes = [
		'r_liteprofile',
		'r_emailaddress',
		'w_member_social',
		// 'w_organization_social',
	];

	public const DRIVER = 'linkedin';

    public function redirect()
    {
        return Socialite::driver(self::DRIVER)->setScopes($this->scopes)->redirect();
    }

    public function callback(Company $company)
    {
        $user = Socialite::driver(self::DRIVER)->user();

        $account = CompanyAccount::firstOrNew([
            'account_id' => $user->getId(),
            'company_id' => $company->id,
        ]);

        $account->medium = self::DRIVER;
        $account->name = $user->getName();
        $account->account_id = $user->getId();
        $account->access_token = $user->token;
        $account->logo = $user->getAvatar();
        $account->type = 'person';
        $account->meta = [
			'refresh_token' => $user->refreshToken
		];

		$company->accounts()->save($account);

		return $account;
    }

    public function post(Post $post, PostAccount $postAccount)
    {
		// https://docs.microsoft.com/en-us/linkedin/marketing/integrations/community-management/shares/posts-api-beta?tabs=http
		$data = [
			'owner' => 'urn:li:'.$postAccount->account->type.':'.$postAccount->account->account_id,
			'text' => [
				'text' => $post->message,
			],
			'distribution' => [
				'linkedInDistributionTarget' => new ArrayObject(),
			],
		];

		if (app()->environment('production') === false) {
		}

        $headers = [
            'Authorization' => 'Bearer ' . $postAccount->account->access_token,
			'Content-Type' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->post('https://api.linkedin.com/v2/shares', $data);

		$postAccount->meta = $response->json();

		$postAccount->save();

		return $postAccount;
    }

    public function uploadAsset()
    {
        // TODO: Implement uploadAsset() method.
    }
}
