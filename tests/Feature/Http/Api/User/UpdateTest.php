<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\User;

use Database\Factories\UserFactory;
use Illuminate\Http\Response;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    public function test()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('put', 'user/' . $user->getKey(), [
            'name'  => 'Kings Code',
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'id'    => $user->getKey(),
            'name'  => 'Kings Code',
            'email' => 'info@kingscode.nl',
        ]);
    }

    public function testValidationErrors()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('put', 'user/' . $user->getKey());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'name', 'email',
        ]);
    }
}
