<?php

namespace App\Http\Libraries\SocialMedia\Drivers;

use App\Http\Libraries\SocialMedia\Contracts\SocialMediaInterface;
use App\Models\Company;

class Instagram implements SocialMediaInterface
{
    public function redirect()
    {
        // TODO: Implement redirect() method.
    }

    public function callback(Company $company)
    {
        // TODO: Implement callback() method.
    }

    public function post(Post $post, PostAccount $postAccount)
    {
        // TODO: Implement post() method.
    }

    public function uploadAsset(Post $post, PostAccount $postAccount)
    {
        // TODO: Implement uploadAsset() method.
    }
}
