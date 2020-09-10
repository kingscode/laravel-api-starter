<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Database\Factories\UserFactory;
use Tests\TestCase;

final class UserTest extends TestCase
{
    public function testUserTokensGetDeleted()
    {
        $user = UserFactory::new()->createOne();
        $token = $user->tokens()->create(['token' => 'yayeet']);

        $user->delete();

        $this->assertDatabaseMissing('user_tokens', [
            'id' => $token->getKey(),
        ]);
    }
}
