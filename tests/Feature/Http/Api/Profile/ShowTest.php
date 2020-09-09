<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Profile;

use Database\Factories\UserFactory;
use Illuminate\Http\Response;
use Tests\TestCase;

final class ShowTest extends TestCase
{
    public function test()
    {
        $user = UserFactory::new()->createOne();

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
