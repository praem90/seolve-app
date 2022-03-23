<?php

namespace App\Http\Libraries\SocialMedia\Drivers;

use App\Http\Libraries\SocialMedia\SocialMediaInterface;

class Twitter implements SocialMediaInterface
{
    public function redirect()
    {
        return ':)';
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
