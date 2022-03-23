<?php

namespace App\Http\Libraries\SocialMedia;

use App\Http\Libraries\SocialMedia\Drivers\Facebook;
use App\Http\Libraries\SocialMedia\Drivers\Instagram;
use App\Http\Libraries\SocialMedia\Drivers\LinkedIn;
use App\Http\Libraries\SocialMedia\Drivers\Twitter;
use Illuminate\Container\Container;
use Illuminate\Support\Manager;

class SocialMediaManager extends Manager
{
    public function with($driver)
    {
        return $this->driver($driver);
    }

    public function createTwitterDriver(): Twitter
    {
        return new Twitter();
    }

    public function createFacebookDriver(): Facebook
    {
        return new Facebook();
    }

    public function createInstagramDriver(): Instagram
    {
        return new Instagram();
    }

    public function createLinkedInDriver(): LinkedIn
    {
        return new LinkedIn();
    }

    public function getDefaultDriver()
    {
        throw new \InvalidArgumentException('No driver was specified.');
    }
}
