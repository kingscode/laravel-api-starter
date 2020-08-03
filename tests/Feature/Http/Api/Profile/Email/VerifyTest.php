<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Profile\Email;

use App\Auth\EmailDispensary;
use App\Models\User;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Response;
use Tests\TestCase;
use function factory;

/**
 * @property EmailDispensary dispensary
 */
final class VerifyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->dispensary = $this->app->make(EmailDispensary::class);
    }

    public function testVerification()
    {
        $email = 'info@kingscode.nl';

        $user = factory(User::class)->create();

        $token = $this->dispensary->dispense($user, $email);

        $response = $this->actingAs($user, 'api')->json('post', 'profile/email/verify', [
            'email' => $email,
            'token' => $token,
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testBadRequestWhenWrongTokenPassed()
    {
        $email = 'info@kingscode.nl';

        $user = factory(User::class)->create();

        $this->dispensary->dispense($user, $email);

        $response = $this->actingAs($user, 'api')->json('post', 'profile/email/verify', [
            'email' => $email,
            'token' => 'zigzagzog',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testBadRequestTokenExpired()
    {
        $email = 'info@kingscode.nl';

        $user = factory(User::class)->create();

        $token = $this->dispensary->dispense($user, $email);

        /** @var Repository $cache */
        $cache = $this->app->make(Repository::class);

        $cache->clear();

        $response = $this->actingAs($user, 'api')->json('post', 'profile/email/verify', [
            'email' => $email,
            'token' => $token,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testValidationErrors()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->json('post', 'profile/email/verify');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email', 'token',
        ]);
    }
}
