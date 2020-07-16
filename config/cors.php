<?php

use App\Http\Header;

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [
        Header::CACHE_CONTROL,
        Header::CONTENT_LANGUAGE,
        Header::CONTENT_LENGTH,
        Header::CONTENT_TYPE,
        Header::EXPIRES,
        Header::LAST_MODIFIED,
        Header::PRAGMA,
        Header::RETRY_AFTER,
    ],

    'max_age' => false,

    'supports_credentials' => false,

];
