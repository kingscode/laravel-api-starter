<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications\User;

use App\Models\User;
use App\Notifications\User\Invitation;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;
use function in_array;

final class InvitationTest extends TestCase
{
    public function testToMailReturnsMailMessage()
    {
        $notification = new Invitation('token');

        $user = User::factory()->createOne();

        $this->assertInstanceOf(MailMessage::class, $notification->toMail($user));
    }

    public function testViaReturnsMailChannel()
    {
        $notification = new Invitation('token');

        $this->assertTrue(in_array('mail', $notification->via()));
    }
}
