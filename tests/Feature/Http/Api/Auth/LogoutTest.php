<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Auth;

use App\Http\Header;
use Database\Factories\UserFactory;
use Tests\TestCase;
use function bcrypt;

final class LogoutTest extends TestCase
{
    public function test()
    {
        $user = UserFactory::new()->createOne([
            'password' => bcrypt('kingscodedotnl'),
        ]);
        $user->tokens()->create(['token' => 'yayeet']);

        $response = $this->json('post', 'auth/logout', [], [
            Header::AUTHORIZATION => 'Bearer yayeet',
        ]);

        $response->assertOk();
    }
}
