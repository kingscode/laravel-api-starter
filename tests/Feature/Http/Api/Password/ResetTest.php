<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Password;

use Database\Factories\UserFactory;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Http\Response;
use Tests\TestCase;

final class ResetTest extends TestCase
{
    public function test()
    {
        $user = UserFactory::new()->createOne();

        $token = $this->app->make(PasswordBrokerManager::class)->broker('users')->createToken($user);

        $response = $this->json('post', 'password/reset', [
            'email'                 => $user->email,
            'token'                 => $token,
            'password'              => 'kingscodedotnl',
            'password_confirmation' => 'kingscodedotnl',
        ]);

        $response->assertOk();;
    }

    public function testWithNonExistentToken()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->json('post', 'password/reset', [
            'email'                 => $user->email,
            'token'                 => 'does-not-exist',
            'password'              => 'kingscodedotnl',
            'password_confirmation' => 'kingscodedotnl',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testValidationErrors()
    {
        $response = $this->json('post', 'password/reset');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'token', 'email', 'password',
        ]);
    }
}
