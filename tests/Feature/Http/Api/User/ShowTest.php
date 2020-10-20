<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\User;

use Database\Factories\UserFactory;
use Tests\TestCase;

final class ShowTest extends TestCase
{
    public function test()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('get', 'user/' . $user->getKey());

        $response->assertOk()->assertJson([
            'data' => [
                'id'    => $user->getKey(),
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}
