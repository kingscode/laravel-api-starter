<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Profile;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    public function test()
    {
        $user = User::factory()->createOne([
            'email' => 'info@kingscode.nl',
        ]);

        $response = $this->actingAs($user, 'api')->json('put', 'profile', [
            'name' => 'King',
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'name' => 'King',
        ]);
    }

    public function testValidationErrors()
    {
        $user = User::factory()->createOne();

        $response = $this->actingAs($user, 'api')->json('put', 'profile');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'name',
        ]);
    }
}
