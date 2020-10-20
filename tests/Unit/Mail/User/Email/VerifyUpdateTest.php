<?php

declare(strict_types=1);

namespace Tests\Unit\Mail\User\Email;

use App\Mail\User\Email\VerifyUpdate;
use Database\Factories\UserFactory;
use Tests\TestCase;

final class VerifyUpdateTest extends TestCase
{
    public function test()
    {
        $user = UserFactory::new()->createOne();

        $mailable = (new VerifyUpdate($user, 'token', 'info@kingscode.nl'));
        $mailable->render();

        $this->assertArrayHasKey('verify_url', $mailable->viewData);
        $this->assertArrayHasKey('user', $mailable->viewData);
        $this->assertStringContainsString('info@kingscode.nl', $mailable->viewData['verify_url']);
    }
}
