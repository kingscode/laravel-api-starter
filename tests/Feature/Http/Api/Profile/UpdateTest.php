<?php

namespace Tests\Feature\Http\Api\Profile;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use function factory;

class UpdateTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create([
            'email' => 'info@kingscode.nl',
        ]);

        $response = $this->actingAs($user)->json('put', 'api/profile', [
            'name'  => 'King',
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'name'  => 'King',
            'email' => 'info@kingscode.nl',
        ]);
    }

    public function testValidationErrorWhenEmailAlreadyInUse()
    {
        $user = factory(User::class)->create();
        factory(User::class)->create([
            'email' => 'info@kingscode.nl',
        ]);

        $response = $this->actingAs($user)->json('put', 'api/profile', [
            'name'  => 'King',
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email',
        ]);
    }

    public function testValidationErrors()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('put', 'api/profile');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'name', 'email',
        ]);
    }
}
