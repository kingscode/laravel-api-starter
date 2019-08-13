<?php

declare(strict_types=1);

namespace App\Support;

/**
 * @param string $uri
 * @return string
 */
function front_url(string $uri = '')
{
    return config('app.front_url') . ($uri ? '/' . $uri : $uri);
}
