<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\User;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

final class IndexTest extends TestCase
{
    public function test()
    {
        $user = User::factory()->createOne();

        $response = $this->actingAs($user, 'api')->json('get', 'user');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            'data' => [
                [
                    'id'    => $user->getKey(),
                    'name'  => $user->name,
                    'email' => $user->email,
                ],
            ],
            'meta' => [
                'current_page' => 1,
                'last_page'    => 1,
                'from'         => 1,
                'to'           => 1,
                'total'        => 1,
                'per_page'     => 15,
            ],
        ]);
    }

    public function testSearchingForName()
    {
        $user1 = User::factory()->createOne([
            'name' => 'aaa',
        ]);
        $user2 = User::factory()->createOne([
            'name' => 'bbb',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?name=bbb');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }

    public function testSearchingForEmail()
    {
        $user1 = User::factory()->createOne([
            'email' => 'info@kingscode.nl',
        ]);
        $user2 = User::factory()->createOne([
            'email' => 'support@kingscode.nl',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?email=support');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByNameAsc()
    {
        $user2 = User::factory()->createOne([
            'name' => 'bbb',
        ]);
        $user1 = User::factory()->createOne([
            'name' => 'aaa',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sort_by=name&desc=0');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user1->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByNameDesc()
    {
        $user2 = User::factory()->createOne([
            'name' => 'bbb',
        ]);
        $user1 = User::factory()->createOne([
            'name' => 'aaa',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sort_by=name&desc=1');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByEmailAsc()
    {
        $user1 = User::factory()->createOne([
            'email' => 'a@kingscode.nl',
        ]);
        $user2 = User::factory()->createOne([
            'email' => 'b@kingscode.nl',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sort_by=email&desc=0');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user1->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByEmailDesc()
    {
        $user1 = User::factory()->createOne([
            'email' => 'a@kingscode.nl',
        ]);
        $user2 = User::factory()->createOne([
            'email' => 'b@kingscode.nl',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sort_by=email&desc=1');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }
}
