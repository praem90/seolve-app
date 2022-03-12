<?php

namespace App\Http\Controllers\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwitterCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $twitter = new TwitterOAuth(
            config('services.twitter.consumer_key'),
            config('services.twitter.consumer_secret'),
            $request->get('oauth_token'),
            $request->get('oauth_verifier')
        );

        $accessToken = $twitter->oauth('oauth/access_token', [
            'oauth_verifier' => $request->get('oauth_verifier')
        ]);

        //Todo::store $accessToken['oauth_token'], $accessToken['oauth_token_secret']

        return redirect('/');

//        $post = new TwitterOAuth(
//            config('services.twitter.consumer_key'),
//            config('services.twitter.consumer_secret'),
//            $accessToken['oauth_token'],
//            $accessToken['oauth_token_secret']
//        );
//
//        $post->setTimeouts(10, 15);
//
//        $post->upload('media/upload', ['media' => '@' . base_path() . '/public/images/icons/facebook.png']);
//        $post->post('statuses/update', [
//            'status' => 'Hello Seolve! sdzc'
//        ]);
//
//        //$post->get('users/search', ['q' => 'Gangai_Amaran', 'count' => 10]);
//
//        dd($post);
    }
}
