<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Registration;

use App\Auth\RegistrationDispensary;
use App\Models\User;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Response;
use Tests\TestCase;
use function factory;

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

        $user = factory(User::class)->create([
            'email' => $email,
        ]);

        $token = $this->dispensary->dispense($user);

        $response = $this->actingAs($user, 'api')->json('post', 'registration/verify', [
            'email'                 => $email,
            'token'                 => $token,
            'password'              => 'secretatleast10charpassword',
            'password_confirmation' => 'secretatleast10charpassword',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testBadRequestWhenWrongEmail()
    {
        $user = factory(User::class)->create([
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

        $user = factory(User::class)->create([
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

        $user = factory(User::class)->create([
            'email' => $email,
        ]);

        $token = $this->dispensary->dispense($user);

        /** @var Repository $cache */
        $cache = $this->app->make(Repository::class);

        $cache->clear();

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
