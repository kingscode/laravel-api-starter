<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Invitation;

use App\Models\User;
use App\Notifications\User\Invitation;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Http\Response;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Tests\TestCase;

final class ResendTest extends TestCase
{
    public function testNotificationDispatched()
    {
        $this->app->instance(Dispatcher::class, $fake = new NotificationFake());

        $email = 'info@kingscode.nl';

        $user = User::factory()->createOne([
            'email' => $email,
        ]);

        $response = $this->json('post', 'invitation/resend', [
            'email' => $email,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $fake->assertSentTo($user, Invitation::class);
    }

    public function testNotificationNotDispatched()
    {
        $this->app->instance(Dispatcher::class, $fake = new NotificationFake());

        $email = 'info@kingscode.nl';

        $response = $this->json('post', 'invitation/resend', [
            'email' => $email,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $fake->assertNothingSent();
    }
}
