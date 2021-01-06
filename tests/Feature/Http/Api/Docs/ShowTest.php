<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Docs;

use Illuminate\Http\Response;
use Tests\TestCase;

final class ShowTest extends TestCase
{
    public function testNoParamReturnsReadMe()
    {
        $response = $this->get('docs');

        $response->assertStatus(Response::HTTP_OK)->assertSeeText('Docs');
    }

    public function testCanRequestReadMe()
    {
        $response = $this->get('docs/readme');

        $response->assertStatus(Response::HTTP_OK)->assertSeeText('Docs');
    }

    public function testNotFoundOnNonExistentDocs()
    {
        $response = $this->get('docs/sprungli');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
