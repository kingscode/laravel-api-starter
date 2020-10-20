<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Registration;

use App\Auth\Dispensary\Repository;
use App\Auth\RegistrationDispensary;
use Database\Factories\UserFactory;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @property \App\Auth\RegistrationDispensary dispensary
 */
final class VerifyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->dispensary = $this->app->make(RegistrationDispensary::class);
    }

    public function testVerification()
    {
        $email = 'info@kingscode.nl';

        $user = UserFactory::new()->createOne([
            'email' => $email,
        ]);

        $token = $this->dispensary->dispense($user);

        $response = $this->actingAs($user, 'api')->json('post', 'registration/verify', [
            'email'                 => $email,
            'token'                 => $token,
            'password'              => 'secretatleast10charpassword',
            'password_confirmation' => 'secretatleast10charpassword',
        ]);

        $response->assertOk();
    }

    public function testBadRequestWhenWrongEmail()
    {
        $user = UserFactory::new()->createOne([
            'email' => 'wrong@kingscode.nl',
        ]);

        $token = $this->dispensary->dispense($user);

        $response = $this->actingAs($user, 'api')->json('post', 'registration/verify', [
            'email'                 => 'info@kingscode.nl',
            'token'                 => $token,
            'password'              => 'secretatleast10charpassword',
            'password_confirmation' => 'secretatleast10charpassword',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testBadRequestWhenWrongTokenPassed()
    {
        $email = 'info@kingscode.nl';

        $user = UserFactory::new()->createOne([
            'email' => $email,
        ]);

        $this->dispensary->dispense($user);

        $response = $this->actingAs($user, 'api')->json('post', 'registration/verify', [
            'email'                 => $email,
            'token'                 => 'zigzagzog',
            'password'              => 'secretatleast10charpassword',
            'password_confirmation' => 'secretatleast10charpassword',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testBadRequestTokenExpired()
    {
        $email = 'info@kingscode.nl';

        $user = UserFactory::new()->createOne([
            'email' => $email,
        ]);

        $token = $this->dispensary->dispense($user);

        /** @var Repository $repository */
        $repository = $this->app->make(Repository::class);

        $repository->clear();

        $response = $this->actingAs($user, 'api')->json('post', 'registration/verify', [
            'email'                 => $email,
            'token'                 => $token,
            'password'              => 'secretatleast10charpassword',
            'password_confirmation' => 'secretatleast10charpassword',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testValidationErrors()
    {
        $response = $this->json('post', 'registration/verify');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email', 'token', 'password',
        ]);
    }
}
