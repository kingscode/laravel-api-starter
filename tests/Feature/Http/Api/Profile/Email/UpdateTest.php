<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Profile\Email;

use App\Models\User;
use App\Notifications\User\Email\CantUpdate;
use App\Notifications\User\Email\VerifyUpdate;
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

        $user = User::factory()->createOne([
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

        User::factory()->createOne([
            'email' => $email,
        ]);

        $user = User::factory()->createOne([
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
        $user = User::factory()->createOne();

        $response = $this->actingAs($user, 'api')->json('put', 'profile/email');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email',
        ]);
    }
}
