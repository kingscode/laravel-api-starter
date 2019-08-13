<?php

namespace Tests\Feature\Http\Api\Profile;

use App\Models\User;
use Tests\TestCase;

class ShowTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('get', 'api/profile');

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id'    => $user->getKey(),
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}
