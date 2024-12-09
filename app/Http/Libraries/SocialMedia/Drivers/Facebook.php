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
    public const DRIVER = 'facebook';

    protected $scopes = [
        'pages_manage_posts',
        'publish_video',
        'instagram_basic',
        'instagram_content_publish',
        'pages_show_list'
    ];

    public function redirect()
    {
        $driver =  Socialite::driver(self::DRIVER);
        $driver->scopes($this->scopes);

        return $driver->redirect();
    }

    public function callback(Company $company)
    {
        $user = Socialite::driver(self::DRIVER)->user();

        $access_token = $this->exchangeToken($user->token);

        $accounts = [];

        $account = CompanyAccount::firstOrNew([
            'account_id' => $user['id'],
            'company_id' => $company->id,
        ]);

        $account->medium = self::DRIVER;
        $account->name = $user['name'];
        $account->account_id = $user['id'];
        $account->access_token = $user->token;
        $account->logo = $this->getProfilePicture($user->getId(), $user->token) ?? url('images/icons/facebook.png');
        $account->type = 'person';
        $account->meta = $user;

        $accounts[] = $account;

        $pages = $this->getPages($access_token);
        foreach ($pages as $page) {
            $account = CompanyAccount::firstOrNew([
                'account_id' => $page['id'],
                'company_id' => $company->id,
            ]);

            $account->medium = self::DRIVER;
            $account->name = $page['name'];
            $account->account_id = $page['id'];
            $account->access_token = $page['access_token'];
            $account->logo = $this->getProfilePicture($page['id'], $page['access_token']) ?? url('images/icons/facebook.png');
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

    public function getProfilePicture($id, $access_token)
    {
        $url = 'https://graph.facebook.com/' . $id . '/picture';
        $response = Http::get($url, ['access_token' => $access_token, 'redirect' => 0]);

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
            $data['published'] = 'true';
            $data['debug'] = 'all';
        }

        $medias = $this->uploadAsset($post, $postAccount);

        if ($medias) {
            $data['attached_media'] = array_map(function ($id) {
                return ['media_fbid' => $id];
            }, $medias);
        }

        $response = Http::post('https://graph.facebook.com/v21.0/' . $postAccount->account->account_id . '/feed', $data);

        $postAccount->meta = $response->json();

        if ($response->successful()) {
            $postAccount->social_media_post_id = $response->json('id');
        }

        $postAccount->save();
    }

    public function uploadAsset(Post $post, PostAccount $postAccount)
    {
         $data = [
            'access_token' => $postAccount->account->access_token,
         ];

         $data['published'] = 'false';

         $media_ids = [];
         foreach ($post->assets as $asset) {
             $client = Http::asMultipart()->attach('source', fopen($asset->getStoragePath(), 'r'));

             $response = $client->post(
                 'https://graph.facebook.com/' . $postAccount->account->account_id . ($asset->isPhoto() ? '/photos' : '/videos'),
                 $data
             );

             if ($response->successful()) {
                 $media_ids[] = $response->json('id');
             }
         }

         return $media_ids;
    }
}
