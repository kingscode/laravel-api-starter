<?php

namespace Tests\Feature\Http\Api\Profile;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

final class ShowTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('get', 'profile');

        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'data' => [
                'id'    => $user->getKey(),
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}
