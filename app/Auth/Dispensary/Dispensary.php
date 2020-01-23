<?php

declare(strict_types=1);

namespace App\Auth\Dispensary;

use App\Auth\Dispensary\Exceptions\TokenExpired;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;
use function class_basename;

final class Dispensary
{
    private Repository $cache;

    private Hasher $hasher;

    private int $ttl;

    private int $chars;

    public function __construct(Repository $cache, Hasher $hasher, int $ttl = 60, int $chars = 128)
    {
        $this->cache = $cache;
        $this->hasher = $hasher;
        $this->ttl = $ttl;
        $this->chars = $chars;
    }

    public function dispense(Authenticatable $user): string
    {
        $token = $this->generateToken();

        $this->cache->put($this->getCacheKey($user), $this->hasher->make($token), $this->ttl);

        return $token;
    }

    /**
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string                                     $token
     * @return bool
     * @throws \App\Auth\Dispensary\Exceptions\TokenExpired
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function verify(Authenticatable $user, string $token): bool
    {
        $hashedToken = $this->cache->get($this->getCacheKey($user));

        if (null === $hashedToken) {
            throw new TokenExpired();
        }

        return $this->hasher->check($token, $hashedToken);
    }

    private function generateToken(): string
    {
        return Str::random($this->chars);
    }

    private function getCacheKey(Authenticatable $user)
    {
        return implode('_', [
            class_basename($user),
            'Token',
            $user->getAuthIdentifier(),
        ]);
    }
}
