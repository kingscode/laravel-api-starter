<?php

declare(strict_types=1);

namespace App\SPA;

use Illuminate\Support\Str;
use function http_build_query;

final class UrlGenerator
{
    private string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function to(string $path, array $query = []): string
    {
        $query = array_filter($query);

        if (count($query) === 0) {
            return $this->format($path);
        }

        return $this->format($path) . '?' . http_build_query($query);
    }

    private function format(string $path): string
    {
        return $this->baseUrl . (Str::startsWith($path, '/') ? $path : '/' . $path);
    }
}
