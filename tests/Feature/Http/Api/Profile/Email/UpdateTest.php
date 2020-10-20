<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Profile\Email;

use App\Notifications\User\Email\CantUpdate;
use App\Notifications\User\Email\VerifyUpdate;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Http\Response;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    public function testVerifyEmailIsSent()
    {
        $this->app->instance(Dispatcher::class, $fake = new NotificationFake());

        $email = 'info@kingscode.nl';

        $user = UserFactory::new()->createOne([
            'email' => $email,
        ]);

        $response = $this->actingAs($user, 'api')->json('put', 'profile/email', [
            'email' => $email,
        ]);

        $response->assertOk();

        $fake->assertSentTo($user, VerifyUpdate::class);
    }

    public function testCantUpdateEmailIsSent()
    {
        $this->app->instance(Dispatcher::class, $fake = new NotificationFake());

        $email = 'info@kingscode.nl';

        UserFactory::new()->createOne([
            'email' => $email,
        ]);

        $user = UserFactory::new()->createOne([
            'email' => 'yoink@dadoink.nl',
        ]);

        $response = $this->actingAs($user, 'api')->json('put', 'profile/email', [
            'email' => $email,
        ]);

        $response->assertOk();

        $fake->assertSentTo($user, CantUpdate::class);
    }

    public function testValidationErrors()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('put', 'profile/email');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email',
        ]);
    }
}
