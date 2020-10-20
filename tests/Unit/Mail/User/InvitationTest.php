<?php

declare(strict_types=1);

namespace Tests\Unit\Mail\User;

use App\Mail\User\Invitation;
use Database\Factories\UserFactory;
use Tests\TestCase;

final class InvitationTest extends TestCase
{
    public function test()
    {
        $user = UserFactory::new()->createOne();

        $mailable = (new Invitation($user, 'info@kingscode.nl'));
        $mailable->render();

        $this->assertArrayHasKey('acceptation_url', $mailable->viewData);
        $this->assertArrayHasKey('user', $mailable->viewData);
        $this->assertStringContainsString('info@kingscode.nl', $mailable->viewData['acceptation_url']);
    }
}
