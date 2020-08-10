<?php

declare(strict_types=1);

namespace App\Auth\Dispensary;

use App\Models\Dispense;
use Carbon\Carbon;

final class Repository
{
    public function put(string $key, string $token, int $ttl): void
    {
        Dispense::query()->updateOrCreate(['key' => $key], [
            'token'      => $token,
            'expires_at' => Carbon::now()->addSeconds($ttl),
        ]);
    }

    public function get(string $key): ?string
    {
        $dispense = Dispense::query()->where('key', $key)->first();

        if (! $dispense instanceof Dispense) {
            return null;
        }

        if ($dispense->getExpiresAt()->lessThan(Carbon::now())) {
            $dispense->delete();

            return null;
        }

        return $dispense->getToken();
    }

    public function clear(): void
    {
        Dispense::query()->delete();
    }
}
