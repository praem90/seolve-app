<?php

namespace App\Http\Libraries\SocialMedia;

interface SocialMediaInterface
{
    public function redirect();

    public function callback();

    public function post();

    public function uploadAsset();
}
