<?php

namespace Tests\Feature\Api\Password;

use Tests\TestCase;

class ForgottenTest extends TestCase
{
    public function test()
    {
        $response = $this->json('post', 'api/password/forgotten', [
            'email' => 'info@kingscode.nl',
        ]);

        $response->assertStatus(200);
    }

    public function testValidationErrors()
    {
        $response = $this->json('post', 'api/password/forgotten');

        $response->assertStatus(422)->assertJsonValidationErrors([
            'email',
        ]);
    }
}
