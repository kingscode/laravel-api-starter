<?php

declare(strict_types=1);

namespace App\Auth;

use App\Auth\Dispensary\Dispensary;
use Illuminate\Contracts\Auth\Authenticatable;
use function class_basename;

final class EmailDispensary
{
    const TTL = 1800;
    const CHARS = 128;

    private Dispensary $dispensary;

    public function __construct(Dispensary $dispensary)
    {
        $this->dispensary = $dispensary;
    }

    public function dispense(Authenticatable $user, string $email): string
    {
        return $this->dispensary->dispense($this->getKey($user, $email), self::TTL, self::CHARS);
    }

    public function verify(Authenticatable $user, string $email, string $token): bool
    {
        return $this->dispensary->verify($this->getKey($user, $email), $token);
    }

    private function getKey(Authenticatable $user, string $email): string
    {
        return implode('_', [
            class_basename($user),
            $user->getAuthIdentifier(),
            'email',
            $email,
        ]);
    }
}
