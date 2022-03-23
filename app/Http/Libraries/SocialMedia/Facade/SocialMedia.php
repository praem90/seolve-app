<?php

namespace App\Http\Libraries\SocialMedia\Facade;

use App\Http\Libraries\SocialMedia\Contracts\Factory;
use Illuminate\Support\Facades\Facade;

class SocialMedia extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
