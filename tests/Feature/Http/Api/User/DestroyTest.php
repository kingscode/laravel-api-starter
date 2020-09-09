<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\User;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

final class DestroyTest extends TestCase
{
    public function test()
    {
        $user1 = User::factory()->createOne();
        $user2 = User::factory()->createOne();

        $response = $this->actingAs($user1, 'api')->json('delete', 'user/' . $user2->getKey());

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('users', [
            'id' => $user2->getKey(),
        ]);
    }

    public function testCantDeleteYourself()
    {
        $user = User::factory()->createOne();

        $response = $this->actingAs($user, 'api')->json('delete', 'user/' . $user->getKey());

        $response->assertStatus(Response::HTTP_CONFLICT);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
        ]);
    }
}
