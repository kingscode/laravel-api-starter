<?php

namespace Tests\Feature\Http\Api\Password;

use App\Models\User;
use function factory;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Tests\TestCase;

class ResetTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

        $token = $this->app->make(PasswordBrokerManager::class)->broker('users')->createToken($user);

        $response = $this->json('post', 'api/password/reset', [
            'email'                 => $user->email,
            'token'                 => $token,
            'password'              => 'kingscodedotnl',
            'password_confirmation' => 'kingscodedotnl',
        ]);

        $response->assertStatus(200);
    }

    public function testWithNonExistentToken()
    {
        $user = factory(User::class)->create();

        $response = $this->json('post', 'api/password/reset', [
            'email'                 => $user->email,
            'token'                 => 'does-not-exist',
            'password'              => 'kingscodedotnl',
            'password_confirmation' => 'kingscodedotnl',
        ]);

        $response->assertStatus(422);
    }

    public function testValidationErrors()
    {
        $response = $this->json('post', 'api/password/reset');

        $response->assertStatus(422)->assertJsonValidationErrors([
            'token', 'email', 'password',
        ]);
    }
}
