<?php

declare(strict_types=1);

namespace Tests\Unit\Mail\User\Email;

use App\Mail\User\Email\CantUpdate;
use Database\Factories\UserFactory;
use Tests\TestCase;

final class CantUpdateTest extends TestCase
{
    public function test()
    {
        $user = UserFactory::new()->createOne();

        $mailable = (new CantUpdate($user));
        $mailable->render();

        $this->assertArrayHasKey('user', $mailable->viewData);
    }
}
