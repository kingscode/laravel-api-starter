<?php

namespace Tests\Feature\Http\Api\User;

use App\Models\User;
use Tests\TestCase;
use function factory;

class DestroyTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs(factory(User::class)->create())->json('delete', 'api/user/' . $user->getKey());

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->getKey(),
        ]);
    }
}
