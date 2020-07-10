<?php

namespace Tests\Feature\Http\Api\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use function bcrypt;
use function factory;

final class LoginTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $response = $this->json('post', 'auth/login', [
            'email'    => $user->email,
            'password' => 'kingscodedotnl',
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                'token',
            ],
        ]);
    }

    public function testNonExistentEmail()
    {
        $response = $this->json('post', 'auth/login', [
            'email'    => 'info@kingscode.nl',
            'password' => 'kingscodedotnl',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email',
        ]);
    }

    public function testWrongPassword()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $response = $this->json('post', 'auth/login', [
            'email'    => $user->email,
            'password' => 'pooper',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email',
        ]);
    }

    public function testValidationErrors()
    {
        $response = $this->json('post', 'auth/login');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email', 'password',
        ]);
    }
}
