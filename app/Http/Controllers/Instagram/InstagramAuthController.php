<?php

namespace App\Http\Controllers\Instagram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class InstagramAuthController extends Controller
{
    public function index()
    {
        Http::get('https://api.instagram.com/v1/users/self/?access_token='.session('access_token'));
        return view('instagram.index');
    }

    public function redirect()
    {
        return Socialite::driver('instagram')->redirect();
    }

    public function callback(Request $request)
    {
        $user = Socialite::driver('instagram')->user();
        $request->session()->put('access_token', $user->token);
        return redirect('/instagram');
    }

    public function logout()
    {
        session()->forget('access_token');
        return redirect('/instagram');
    }

    public function getUser()
    {
        $user = Http::get('https://api.instagram.com/v1/users/self/?access_token='.session('access_token'));
        return $user;
    }

    public function getMedia()
    {
        $media = Http::get('https://api.instagram.com/v1/users/self/media/recent/?access_token='.session('access_token'));
        return $media;
    }

    public function getMediaByTag($tag)
    {
        $media = Http::get('https://api.instagram.com/v1/tags/'.$tag.'/media/recent?access_token='.session('access_token'));
        return $media;
    }

    public function getMediaByLocation($location)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token'));
        return $media;
    }

    public function getMediaByUser($user)
    {
        $media = Http::get('https://api.instagram.com/v1/users/'.$user.'/media/recent/?access_token='.session('access_token'));
        return $media;
    }

    public function getMediaByCode($code)
    {
        $media = Http::get('https://api.instagram.com/v1/media/'.$code.'?access_token='.session('access_token'));
        return $media;
    }

    public function getMediaByLocationId($location)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token'));
        return $media;
    }

    public function getMediaByLocationIdWithParams($location, $params)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token').$params);
        return $media;
    }

    public function getMediaByLocationIdWithParamsAndLimit($location, $params, $limit)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token').$params.'&count='.$limit);
        return $media;
    }

    public function getMediaByLocationIdWithParamsAndLimitAndMinId($location, $params, $limit, $min_id)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token').$params.'&count='.$limit.'&min_id='.$min_id);
        return $media;
    }

    public function getMediaByLocationIdWithParamsAndLimitAndMaxId($location, $params, $limit, $max_id)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token').$params.'&count='.$limit.'&max_id='.$max_id);
        return $media;
    }

    public function getMediaByLocationIdWithParamsAndLimitAndMinIdAndMaxId($location, $params, $limit, $min_id, $max_id)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token').$params.'&count='.$limit.'&min_id='.$min_id.'&max_id='.$max_id);
        return $media;
    }

    public function getMediaByLocationIdWithParamsAndLimitAndMinIdAndMaxIdAndDistance($location, $params, $limit, $min_id, $max_id, $distance)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token').$params.'&count='.$limit.'&min_id='.$min_id.'&max_id='.$max_id.'&distance='.$distance);
        return $media;
    }

    public function getMediaByLocationIdWithParamsAndLimitAndMinIdAndMaxIdAndDistanceAndMinTimestamp($location, $params, $limit, $min_id, $max_id, $distance, $min_timestamp)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token').$params.'&count='.$limit.'&min_id='.$min_id.'&max_id='.$max_id.'&distance='.$distance.'&min_timestamp='.$min_timestamp);
        return $media;
    }

    public function getMediaByLocationIdWithParamsAndLimitAndMinIdAndMaxIdAndDistanceAndMinTimestampAndMaxTimestamp($location, $params, $limit, $min_id, $max_id, $distance, $min_timestamp, $max_timestamp)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token').$params.'&count='.$limit.'&min_id='.$min_id.'&max_id='.$max_id.'&distance='.$distance.'&min_timestamp='.$min_timestamp.'&max_timestamp='.$max_timestamp);
        return $media;
    }

    public function getMediaByLocationIdWithParamsAndLimitAndMinIdAndMaxIdAndDistanceAndMinTimestampAndMaxTimestampAndLatitudeAndLongitude($location, $params, $limit, $min_id, $max_id, $distance, $min_timestamp, $max_timestamp, $latitude, $longitude)
    {
        $media = Http::get('https://api.instagram.com/v1/locations/'.$location.'/media/recent?access_token='.session('access_token').$params.'&count='.$limit.'&min_id='.$min_id.'&max_id='.$max_id.'&distance='.$distance.'&min_timestamp='.$min_timestamp.'&max_timestamp='.$max_timestamp.'&lat='.$latitude.'&lng='.$longitude);
        return $media;
    }


}
