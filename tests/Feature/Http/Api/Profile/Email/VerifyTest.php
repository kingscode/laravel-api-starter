<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Profile\Email;

use App\Auth\Dispensary\Repository;
use App\Auth\EmailDispensary;
use Database\Factories\UserFactory;
use Illuminate\Http\Response;
use Tests\TestCase;

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

        $user = UserFactory::new()->createOne();

        $token = $this->dispensary->dispense($user, $email);

        $response = $this->actingAs($user, 'api')->json('post', 'profile/email/verify', [
            'email' => $email,
            'token' => $token,
        ]);

        $response->assertOk();
    }

    public function testBadRequestWhenWrongTokenPassed()
    {
        $email = 'info@kingscode.nl';

        $user = UserFactory::new()->createOne();

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

        $user = UserFactory::new()->createOne();

        $token = $this->dispensary->dispense($user, $email);

        /** @var Repository $repository */
        $repository = $this->app->make(Repository::class);

        $repository->clear();

        $response = $this->actingAs($user, 'api')->json('post', 'profile/email/verify', [
            'email' => $email,
            'token' => $token,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testValidationErrors()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('post', 'profile/email/verify');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email', 'token',
        ]);
    }
}
