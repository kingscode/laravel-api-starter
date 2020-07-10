<?php

namespace Tests\Feature\Http\Api\User;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use function factory;

final class UpdateTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('put', 'user/' . $user->getKey(), [
            'name'  => 'Kings Code',
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id'    => $user->getKey(),
            'name'  => 'Kings Code',
            'email' => 'info@kingscode.nl',
        ]);
    }

    public function testValidationErrors()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('put', 'user/' . $user->getKey());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'name', 'email',
        ]);
    }
}
