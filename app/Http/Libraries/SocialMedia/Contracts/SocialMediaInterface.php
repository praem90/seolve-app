<?php

namespace App\Http\Libraries\SocialMedia\Contracts;

interface SocialMediaInterface
{
    public function redirect();

    public function callback();

    public function post();

    public function uploadAsset();
}