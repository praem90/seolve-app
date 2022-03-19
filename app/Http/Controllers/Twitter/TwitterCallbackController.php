<?php

namespace App\Http\Controllers\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyAccount;
use Illuminate\Http\Request;

class TwitterCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $companyId = cache('oauth.1');
        $company = Company::whereId($companyId)->exists();

        abort_unless($companyId && $company, 404);

        $twitter = new TwitterOAuth(
            config('services.twitter.consumer_key'),
            config('services.twitter.consumer_secret'),
            $request->get('oauth_token'),
            $request->get('oauth_verifier')
        );

        $accessToken = $twitter->oauth('oauth/access_token', [
            'oauth_verifier' => $request->get('oauth_verifier')
        ]);

        $user = new TwitterOAuth(
            config('services.twitter.consumer_key'),
            config('services.twitter.consumer_secret'),
            $accessToken['oauth_token'],
            $accessToken['oauth_token_secret']
        );

        $profile = $user->get('account/verify_credentials');

        $account = CompanyAccount::firstOrNew([
            'account_id' => $profile->id,
            'company_id' => $companyId,
        ]);

        $account->company_id = $companyId;
        $account->medium = 'twitter';
        $account->name = $profile->name;
        $account->account_id = $profile->id;
        $account->access_token = $accessToken['oauth_token'];
        $account->access_token_secret = $accessToken['oauth_token_secret'];
        $account->logo = $profile->profile_image_url;
        $account->type = 'user';
        $account->meta = $profile;
        $account->save();

        return redirect('/company/'. $companyId);
    }
}
