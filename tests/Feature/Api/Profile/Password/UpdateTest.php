<?php

namespace Tests\Feature\Api\Profile\Password;

use App\Models\User;
use Tests\TestCase;
use function factory;

class UpdateTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('put', 'api/profile/password', [
            'password'              => 'kingscodedotnl',
            'password_confirmation' => 'kingscodedotnl',
        ]);

        $response->assertStatus(200);
    }

    public function testValidationErrors()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('put', 'api/profile/password');

        $response->assertStatus(422)->assertJsonValidationErrors([
            'password',
        ]);
    }
}
