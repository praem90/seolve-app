<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => 'https://seolve.test/oauth/facebook/callback',
    ],

    'twitter' => [
        'consumer_key' => env('TWITTER_API_KEY'),
        'consumer_secret' => env('TWITTER_API_SECRET'),
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'access_token' => env('TWITTER_ACCESS_TOKEN'),
        'access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
        'redirect' => 'https://seolve.test/oauth/twitter/callback',
    ],

    'instagram' => [
        'client_id' => env('INSTAGRAM_CLIENT_ID'),
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),
        'redirect' => env('INSTAGRAM_REDIRECT_URI')
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID', '86hmxzz7gux7oq'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET', 'e6LNtZyIoUoYOlAH'),
        'redirect' => 'https://seolve.test/oauth/linkedin/callback',
    ],


];
