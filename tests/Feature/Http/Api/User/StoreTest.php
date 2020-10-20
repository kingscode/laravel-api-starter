<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\User;

use Database\Factories\UserFactory;
use Illuminate\Http\Response;
use Tests\TestCase;

final class StoreTest extends TestCase
{
    public function test()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('post', 'user', [
            'name'  => 'Kings Code',
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'name'  => 'Kings Code',
            'email' => 'info@kingscode.nl',
        ]);
    }

    public function testValidationErrors()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('post', 'user');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'name', 'email',
        ]);
    }
}
