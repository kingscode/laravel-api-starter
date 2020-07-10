<?php

namespace Tests\Unit\Notifications\User;

use App\Models\User;
use App\Notifications\User\PasswordReset;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

final class PasswordResetTest extends TestCase
{
    public function testToMailReturnsMailMessage()
    {
        $notification = new PasswordReset('token');

        $user = factory(User::class)->create();

        $this->assertInstanceOf(MailMessage::class, $notification->toMail($user));
    }

    public function testViaReturnsMailChannel()
    {
        $notification = new PasswordReset('token');

        $this->assertTrue(in_array('mail', $notification->via()));
    }
}
