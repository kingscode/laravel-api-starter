<?php

namespace Tests\Feature\Http\Api\Password;

use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Http\Response;
use Tests\TestCase;
use function factory;

class AcceptTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

        $token = $this->app->make(PasswordBrokerManager::class)->broker('user-invitations')->createToken($user);

        $response = $this->json('post', 'invitation/accept', [
            'email'                 => $user->email,
            'token'                 => $token,
            'password'              => 'kingscodedotnl',
            'password_confirmation' => 'kingscodedotnl',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testWithNonExistentToken()
    {
        $user = factory(User::class)->create();

        $response = $this->json('post', 'invitation/accept', [
            'email'                 => $user->email,
            'token'                 => 'does-not-exist',
            'password'              => 'kingscodedotnl',
            'password_confirmation' => 'kingscodedotnl',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testValidationErrors()
    {
        $response = $this->json('post', 'invitation/accept');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'token', 'email', 'password',
        ]);
    }
}
