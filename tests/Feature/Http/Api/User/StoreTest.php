<?php

namespace Tests\Feature\Http\Api\User;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use function factory;

final class StoreTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('post', 'user', [
            'name'  => 'Kings Code',
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name'  => 'Kings Code',
            'email' => 'info@kingscode.nl',
        ]);
    }

    public function testValidationErrors()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('post', 'user');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'name', 'email',
        ]);
    }
}
