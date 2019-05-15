<?php

namespace Tests\Feature\Http\Api\User;

use App\Models\User;
use Tests\TestCase;
use function factory;

class UpdateTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('put', 'api/user/' . $user->getKey(), [
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

        $response = $this->actingAs($user)->json('put', 'api/user/' . $user->getKey());

        $response->assertStatus(422)->assertJsonValidationErrors([
            'name', 'email',
        ]);
    }
}
