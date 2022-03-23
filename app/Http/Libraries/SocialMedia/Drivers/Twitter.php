<?php

namespace App\Http\Libraries\SocialMedia\Drivers;

use App\Http\Libraries\SocialMedia\Contracts\SocialMediaInterface;
use Laravel\Socialite\Facades\Socialite;

class Twitter implements SocialMediaInterface
{
    public function redirect()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function callback()
    {
        // TODO: Implement callback() method.
    }

    public function post()
    {
        // TODO: Implement post() method.
    }

    public function uploadAsset()
    {
        // TODO: Implement uploadAsset() method.
    }
}
