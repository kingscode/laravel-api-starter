<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\User;

use Database\Factories\UserFactory;
use Tests\TestCase;

final class IndexTest extends TestCase
{
    public function test()
    {
        $user = UserFactory::new()->createOne();

        $response = $this->actingAs($user, 'api')->json('get', 'user');

        $response->assertOk()->assertExactJson([
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
        $user1 = UserFactory::new()->createOne([
            'name' => 'aaa',
        ]);
        $user2 = UserFactory::new()->createOne([
            'name' => 'bbb',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?name=bbb');

        $response->assertOk();

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }

    public function testSearchingForEmail()
    {
        $user1 = UserFactory::new()->createOne([
            'email' => 'info@kingscode.nl',
        ]);
        $user2 = UserFactory::new()->createOne([
            'email' => 'support@kingscode.nl',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?email=support');

        $response->assertOk();

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByNameAsc()
    {
        $user2 = UserFactory::new()->createOne([
            'name' => 'bbb',
        ]);
        $user1 = UserFactory::new()->createOne([
            'name' => 'aaa',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sort_by=name&desc=0');

        $response->assertOk();

        $this->assertSame($user1->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByNameDesc()
    {
        $user2 = UserFactory::new()->createOne([
            'name' => 'bbb',
        ]);
        $user1 = UserFactory::new()->createOne([
            'name' => 'aaa',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sort_by=name&desc=1');

        $response->assertOk();

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByEmailAsc()
    {
        $user1 = UserFactory::new()->createOne([
            'email' => 'a@kingscode.nl',
        ]);
        $user2 = UserFactory::new()->createOne([
            'email' => 'b@kingscode.nl',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sort_by=email&desc=0');

        $response->assertOk();

        $this->assertSame($user1->getKey(), $response->json('data.0.id'));
    }

    public function testSortingByEmailDesc()
    {
        $user1 = UserFactory::new()->createOne([
            'email' => 'a@kingscode.nl',
        ]);
        $user2 = UserFactory::new()->createOne([
            'email' => 'b@kingscode.nl',
        ]);

        $response = $this->actingAs($user1, 'api')->json('get', 'user?sort_by=email&desc=1');

        $response->assertOk();

        $this->assertSame($user2->getKey(), $response->json('data.0.id'));
    }
}
