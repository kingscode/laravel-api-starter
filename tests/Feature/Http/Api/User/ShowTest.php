<?php

namespace Tests\Feature\Http\Api\User;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use function factory;

class ShowTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('get', 'api/user/' . $user->getKey());

        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'data' => [
                'id'    => $user->getKey(),
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}
