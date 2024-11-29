<?php

namespace App\Http\Libraries\SocialMedia\Drivers;

use App\Http\Libraries\SocialMedia\Contracts\SocialMediaInterface;
use App\Models\Company;
use App\Models\CompanyAccount;
use App\Models\Post;
use App\Models\PostAccount;
use ArrayObject;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class LinkedIn implements SocialMediaInterface
{
    public $scopes = [
        'openid',
        'profile',
        'w_member_social',
        'email',
        // 'w_organization_social',
    ];

    public const DRIVER = 'linkedin-openid';

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

        $account->medium = 'linkedin';
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
            'owner' => 'urn:li:' . $postAccount->account->type . ':' . $postAccount->account->account_id,
            'text' => [
                'text' => $post->message,
            ],
            'distribution' => [
                'linkedInDistributionTarget' => new ArrayObject(),
            ],
        ];

        if (app()->environment('production') === false) {
        }

        $assets = $this->uploadAsset($post, $postAccount);

        if (count($assets)) {
            $data['contentEntities'] = [];

            foreach ($assets as $asset) {
                $data['contentEntities'][] = [
                    'entity' => $asset,
                ];
            }
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

    public function uploadAsset(Post $post, PostAccount $postAccount)
    {
        $media_ids = [];

        foreach ($post->assets as $asset) {
            $registerUpload = $this->registerUpload($postAccount, $asset->isPhoto() ? 'image' : 'video');
            if ($registerUpload === false) {
                continue;
            }

            $uploadUrl = Arr::get(
                $registerUpload,
                'value.uploadMechanism.com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest.uploadUrl'
            );
            $assetUrn = Arr::get($registerUpload, 'value.uploadMechanism.asset');

            $client = Http::asMultipart()->attach('upload-file', fopen($asset->getStoragePath(), 'r'));
            $response = $client->post($uploadUrl);

            if ($response->successful()) {
                $media_ids[] = $assetUrn;
            }
        }

        return $media_ids;
    }

    public function registerUpload(PostAccount $postAccount, $type)
    {
        $params = [
            'registerUploadRequest' => [
                'owner' => 'urn:li:person:' . $postAccount->account->account_id,
                'recipes' => [
                    'urn:li:digitalmediaRecipe:feedshare-video'
                ],
                'serviceRelationships' => [
                    'identifier' => 'urn:li:userGeneratedContent',
                    'relationshipType' => 'OWNER',
                ],
            ],
        ];


        if ($type === 'image') {
            $params['registerUploadRequest']['recipes'] = [
                'urn:li:digitalmediaRecipe:feedshare-image'
            ];

            $params['registerUploadRequest']['supportedUploadMechanism'] = [
                'SYNCHRONOUS_UPLOAD'
            ];
        }

        $headers = [
            'Authorization' => 'Bearer ' . $postAccount->account->access_token,
            'Content-Type' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->post('https://api.linkedin.com/v2/assets?action=registerUpload', $params);

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }
}
