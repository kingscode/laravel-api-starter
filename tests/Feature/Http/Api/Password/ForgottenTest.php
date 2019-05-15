<?php

namespace Tests\Feature\Http\Api\Password;

use App\Models\User;
use App\Notifications\User\PasswordReset;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Tests\TestCase;

class ForgottenTest extends TestCase
{
    public function testMailGetsSentForNonExistentUser()
    {
        $this->app->instance(Dispatcher::class, $fake = new NotificationFake());

        $user = factory(User::class)->create([
            'email' => 'info@kingscode.nl',
        ]);

        $response = $this->json('post', 'api/password/forgotten', [
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertStatus(200);

        $fake->assertSentTo($user, PasswordReset::class);
    }

    public function testStatusIsOkEvenWhenUserDoesntExist()
    {
        $response = $this->json('post', 'api/password/forgotten', [
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertStatus(200);
    }

    public function testValidationErrors()
    {
        $response = $this->json('post', 'api/password/forgotten');

        $response->assertStatus(422)->assertJsonValidationErrors([
            'email',
        ]);
    }
}
