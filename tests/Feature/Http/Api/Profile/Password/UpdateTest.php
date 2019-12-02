<?php

namespace Tests\Feature\Http\Api\Profile\Password;

use App\Models\User;
use Illuminate\Http\Response;
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
            'current_password'      => 'secret',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCurrentPasswordIncorrect()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('put', 'api/profile/password', [
            'password'              => 'kingscodedotnl',
            'password_confirmation' => 'kingscodedotnl',
            'current_password'      => 'secretiswrong',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'current_password',
        ]);
    }

    public function testValidationErrors()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('put', 'api/profile/password');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'password', 'current_password',
        ]);
    }
}
