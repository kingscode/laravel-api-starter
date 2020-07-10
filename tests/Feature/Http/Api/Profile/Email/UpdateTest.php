<?php

namespace Tests\Feature\Http\Api\Profile\Email;

use App\Models\User;
use App\Notifications\User\Email\CantUpdate;
use App\Notifications\User\Email\VerifyUpdate;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Http\Response;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Tests\TestCase;
use function factory;

final class UpdateTest extends TestCase
{
    public function testVerifyEmailIsSent()
    {
        $this->app->instance(Dispatcher::class, $fake = new NotificationFake());

        $email = 'info@kingscode.nl';

        $user = factory(User::class)->create([
            'email' => $email,
        ]);

        $response = $this->actingAs($user, 'api')->json('put', 'profile/email', [
            'email' => $email,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $fake->assertSentTo($user, VerifyUpdate::class);
    }

    public function testCantUpdateEmailIsSent()
    {
        $this->app->instance(Dispatcher::class, $fake = new NotificationFake());

        $email = 'info@kingscode.nl';

        factory(User::class)->create([
            'email' => $email,
        ]);

        $user = factory(User::class)->create([
            'email' => 'yoink@dadoink.nl',
        ]);

        $response = $this->actingAs($user, 'api')->json('put', 'profile/email', [
            'email' => $email,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $fake->assertSentTo($user, CantUpdate::class);
    }

    public function testValidationErrors()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('put', 'profile/email');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email',
        ]);
    }
}
