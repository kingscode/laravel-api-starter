<?php

declare(strict_types=1);

namespace App\Auth\Dispensary;

use App\Auth\Dispensary\Exceptions\TokenExpiredException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;

final class Dispensary
{
    private Repository $repository;

    private Hasher $hasher;

    public function __construct(Repository $repository, Hasher $hasher)
    {
        $this->repository = $repository;
        $this->hasher = $hasher;
    }

    public function dispense(string $key, int $ttl, int $chars): string
    {
        $token = $this->generateToken($chars);

        $this->repository->put($key, $this->hasher->make($token), $ttl);

        return $token;
    }

    /**
     * @param  string $key
     * @param  string $token
     * @return bool
     * @throws \App\Auth\Dispensary\Exceptions\TokenExpiredException
     */
    public function verify(string $key, string $token): bool
    {
        $hashedToken = $this->repository->get($key);

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
