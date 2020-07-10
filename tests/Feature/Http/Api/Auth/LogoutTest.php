<?php

namespace Tests\Feature\Http\Api\Auth;

use App\Http\Header;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use function bcrypt;
use function factory;

final class LogoutTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('kingscodedotnl'),
        ]);
        $user->tokens()->create(['token' => 'yayeet']);

        $response = $this->json('post', 'auth/logout', [], [
            Header::AUTHORIZATION => 'Bearer yayeet',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }
}
