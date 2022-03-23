<?php

namespace App\Http\Libraries\SocialMedia;

use App\Http\Libraries\SocialMedia\Drivers\Facebook;
use App\Http\Libraries\SocialMedia\Drivers\Instagram;
use App\Http\Libraries\SocialMedia\Drivers\LinkedIn;
use App\Http\Libraries\SocialMedia\Drivers\Twitter;
use Illuminate\Container\Container;
use Illuminate\Support\Manager;

class SocialMediaManager
{
    public function getDefaultDriver()
    {
        abort(404);
    }

    public function getTwitterDriver(): Twitter
    {
        return new Twitter();
    }

    public function getFacebookDriver(): Facebook
    {
        return new Facebook();
    }

    public function getInstagramDriver(): Instagram
    {
        return new Instagram();
    }

    public function getLinkedInDriver(): LinkedIn
    {
        return new LinkedIn();
    }
}
