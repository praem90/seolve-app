<?php

namespace App\Http\Libraries\SocialMedia\Contracts;

interface Factory
{
    public function driver($driver = null);
}
