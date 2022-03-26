<?php

namespace App\Http\Libraries\SocialMedia\Contracts;

use App\Models\Company;
use App\Models\Post;
use App\Models\PostAccount;

interface SocialMediaInterface
{
    public function redirect();

    public function callback(Company $comany);

    public function post(Post $post, PostAccount $postAccount);

    public function uploadAsset(Post $post, PostAccount $postAccount);
}
