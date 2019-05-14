<?php

namespace Tests\Feature\Api\Profile;

use App\Models\User;
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

        $response->assertStatus(200);

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

        $response->assertStatus(422)->assertJsonValidationErrors([
            'email',
        ]);
    }

    public function testValidationErrors()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('put', 'api/profile');

        $response->assertStatus(422)->assertJsonValidationErrors([
            'name', 'email',
        ]);
    }
}
