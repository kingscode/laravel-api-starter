<?php

namespace Tests\Feature\Http\Api\User;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use function factory;

class DestroyTest extends TestCase
{
    public function test()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $response = $this->actingAs($user1, 'api')->json('delete', 'api/user/' . $user2->getKey());

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('users', [
            'id' => $user2->getKey(),
        ]);
    }
}
