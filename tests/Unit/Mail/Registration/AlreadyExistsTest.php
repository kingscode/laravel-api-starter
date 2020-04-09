<?php

declare(strict_types=1);

namespace Tests\Unit\Mail\Registration;

use App\Mail\Registration\AlreadyExists;
use Tests\TestCase;

final class AlreadyExistsTest extends TestCase
{
    public function test()
    {
        $mailable = (new AlreadyExists());

        $this->app->call([$mailable, 'build']);

        $this->assertArrayHasKey('front_end_url', $mailable->viewData);
        $this->assertArrayHasKey('password_forgotten_url', $mailable->viewData);
    }
}
