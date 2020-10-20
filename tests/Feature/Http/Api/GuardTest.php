<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api;

use App\Http\Header;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Tests\TestCase;

final class GuardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        /** @var Router $router */
        $router = $this->app->make(Router::class);

        $router->middleware(['api', 'auth:api'])->get('test', function () {
            return new JsonResponse(['message' => 'ok']);
        });
    }

    public function testUnauthorized()
    {
        $response = $this->json('get', 'test');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testCorrectToken()
    {
        $user = UserFactory::new()->createOne();
        $userToken = $user->tokens()->create(['token' => 'yayeet']);

        $now = $userToken->created_at->copy()->addDays(7);

        Carbon::setTestNow($now);

        $response = $this->json('get', 'test', [], [
            Header::AUTHORIZATION => 'Bearer yayeet',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('user_tokens', [
            'updated_at' => $now,
        ]);
    }
}
