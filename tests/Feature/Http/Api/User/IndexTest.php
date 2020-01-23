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

        $response = $this->actingAs($user, 'api')->json('get', 'api/user');

        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'data' => [
                [
                    'id'    => $user->getKey(),
                    'name'  => $user->name,
                    'email' => $user->email,
                ],
            ],
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

        $response = $this->actingAs($user1, 'api')->json('get', 'api/user?name=bbb');

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

        $response = $this->actingAs($user1, 'api')->json('get', 'api/user?email=support');

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

        $response = $this->actingAs($user1, 'api')->json('get', 'api/user?sortBy=name&desc=0');

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

        $response = $this->actingAs($user1, 'api')->json('get', 'api/user?sortBy=name&desc=1');

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

        $response = $this->actingAs($user1, 'api')->json('get', 'api/user?sortBy=email&desc=0');

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

        $response = $this->actingAs($user1, 'api')->json('get', 'api/user?sortBy=email&desc=1');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }
}
