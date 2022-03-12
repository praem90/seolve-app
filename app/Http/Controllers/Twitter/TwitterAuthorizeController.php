<?php

namespace App\Http\Controllers\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Controllers\Controller;

class TwitterAuthorizeController extends Controller
{
    public function __invoke()
    {
        $twitter = new TwitterOAuth(
            config('services.twitter.consumer_key'),
            config('services.twitter.consumer_secret'),
            config('services.twitter.access_token'),
            config('services.twitter.access_token_secret'),
        );

        $requestToken = $twitter->oauth('oauth/request_token', ['oauth_callback' => config('services.twitter.redirect')]);

        $route = $twitter->url('oauth/authorize', ['oauth_token' => $requestToken['oauth_token']]);

        return redirect($route);
    }
}
