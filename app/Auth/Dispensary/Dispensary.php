<?php

declare(strict_types=1);

namespace App\Auth\Dispensary;

use App\Auth\Dispensary\Exceptions\TokenExpiredException;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;

final class Dispensary
{
    private Repository $cache;

    private Hasher $hasher;

    public function __construct(Repository $cache, Hasher $hasher)
    {
        $this->cache = $cache;
        $this->hasher = $hasher;
    }

    public function dispense(string $cacheKey, int $ttl, int $chars): string
    {
        $token = $this->generateToken($chars);

        $this->cache->put($cacheKey, $this->hasher->make($token), $ttl);

        return $token;
    }

    /**
     * @param  string $cacheKey
     * @param  string $token
     * @return bool
     * @throws \App\Auth\Dispensary\Exceptions\TokenExpiredException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function verify(string $cacheKey, string $token): bool
    {
        $hashedToken = $this->cache->get($cacheKey);

        if (null === $hashedToken) {
            throw new TokenExpiredException();
        }

        return $this->hasher->check($token, $hashedToken);
    }

    private function generateToken(int $chars): string
    {
        return Str::random($chars);
    }
}
