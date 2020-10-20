<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Profile\Password;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Http\Response;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    public function test()
    {
        /** @var User $user */
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('put', 'profile/password', [
            'password'              => 'kingscodedotnl',
            'password_confirmation' => 'kingscodedotnl',
            'current_password'      => 'secret',
        ]);

        $response->assertOk();
    }

    public function testCurrentPasswordIncorrect()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('put', 'profile/password', [
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
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('put', 'profile/password');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'password', 'current_password',
        ]);
    }
}
