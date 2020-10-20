<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\User;

use Database\Factories\UserFactory;
use Illuminate\Http\Response;
use Tests\TestCase;

final class DestroyTest extends TestCase
{
    public function test()
    {
        $user1 = UserFactory::new()->createOne();
        $user2 = UserFactory::new()->createOne();

        $response = $this->actingAs($user1, 'api')->json('delete', "user/{$user2->getKey()}");

        $response->assertOk();

        $this->assertDatabaseMissing('users', [
            'id' => $user2->getKey(),
        ]);
    }

    public function testCantDeleteYourself()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('delete', "user/{$user->getKey()}");

        $response->assertStatus(Response::HTTP_CONFLICT);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
        ]);
    }
}
