<?php

namespace Tests\Feature\Http\Api\User;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use function factory;

class IndexTest extends TestCase
{
    public function test()
    {
        $user = factory(User::class)->create();

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
            ]
        ]);
    }

    public function testSearchingForName()
    {
        $user1 = factory(User::class)->create([
            'name' => 'aaa',
        ]);
        $user2 = factory(User::class)->create([
            'name' => 'bbb',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?name=bbb');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }

    public function testSearchingForEmail()
    {
        $user1 = factory(User::class)->create([
            'email' => 'info@kingscode.nl',
        ]);
        $user2 = factory(User::class)->create([
            'email' => 'support@kingscode.nl',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?email=support');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByNameAsc()
    {
        $user2 = factory(User::class)->create([
            'name' => 'bbb',
        ]);
        $user1 = factory(User::class)->create([
            'name' => 'aaa',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sortBy=name&desc=0');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user1->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByNameDesc()
    {
        $user2 = factory(User::class)->create([
            'name' => 'bbb',
        ]);
        $user1 = factory(User::class)->create([
            'name' => 'aaa',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sortBy=name&desc=1');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByEmailAsc()
    {
        $user1 = factory(User::class)->create([
            'email' => 'a@kingscode.nl',
        ]);
        $user2 = factory(User::class)->create([
            'email' => 'b@kingscode.nl',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sortBy=email&desc=0');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user1->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByEmailDesc()
    {
        $user1 = factory(User::class)->create([
            'email' => 'a@kingscode.nl',
        ]);
        $user2 = factory(User::class)->create([
            'email' => 'b@kingscode.nl',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sortBy=email&desc=1');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }
}
