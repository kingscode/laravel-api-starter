<?php

declare(strict_types=1);

namespace App\Auth;

use App\Auth\Dispensary\Dispensary;
use Illuminate\Contracts\Auth\Authenticatable;
use function class_basename;

final class RegistrationDispensary
{
    const TTL = 1800;
    const CHARS = 128;

    private Dispensary $dispensary;

    public function __construct(Dispensary $dispensary)
    {
        $this->dispensary = $dispensary;
    }

    public function dispense(Authenticatable $user): string
    {
        return $this->dispensary->dispense($this->getKey($user), self::TTL, self::CHARS);
    }

    public function verify(Authenticatable $user, string $token): bool
    {
        return $this->dispensary->verify($this->getKey($user), $token);
    }

    private function getKey(Authenticatable $user): string
    {
        return implode('_', [
            class_basename($user),
            $user->getAuthIdentifier(),
            'agency',
            'registration',
        ]);
    }
}
