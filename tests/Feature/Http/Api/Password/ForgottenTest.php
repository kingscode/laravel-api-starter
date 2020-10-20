<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Password;

use App\Notifications\User\PasswordReset;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Http\Response;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Tests\TestCase;

final class ForgottenTest extends TestCase
{
    public function testMailGetsSentForNonExistentUser()
    {
        $this->app->instance(Dispatcher::class, $fake = new NotificationFake());

        $user = UserFactory::new()->createOne([
            'email' => 'info@kingscode.nl',
        ]);

        $response = $this->json('post', 'password/forgotten', [
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertOk();

        $fake->assertSentTo($user, PasswordReset::class);
    }

    public function testStatusIsOkEvenWhenUserDoesntExist()
    {
        $response = $this->json('post', 'password/forgotten', [
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertOk();
    }

    public function testValidationErrors()
    {
        $response = $this->json('post', 'password/forgotten');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email',
        ]);
    }
}
