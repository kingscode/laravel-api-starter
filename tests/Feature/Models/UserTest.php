<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\User;
use Tests\TestCase;
use function factory;

final class UserTest extends TestCase
{
    public function testUserTokensGetDeleted()
    {
        $user = factory(User::class)->create();
        $token = $user->tokens()->create(['token' => 'yayeet']);

        $user->delete();

        $this->assertDatabaseMissing('user_tokens', [
            'id' => $token->getKey(),
        ]);
    }
}
