<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications\User;

use App\Mail\User\Invitation as InvitationMail;
use App\Notifications\User\Invitation;
use Database\Factories\UserFactory;
use Tests\TestCase;
use function in_array;

final class InvitationTest extends TestCase
{
    public function testToMailReturnsMailMessage()
    {
        $notification = new Invitation('token');

        $user = UserFactory::new()->createOne();

        $this->assertInstanceOf(InvitationMail::class, $notification->toMail($user));
    }

    public function testViaReturnsMailChannel()
    {
        $notification = new Invitation('token');

        $this->assertTrue(in_array('mail', $notification->via()));
    }
}
