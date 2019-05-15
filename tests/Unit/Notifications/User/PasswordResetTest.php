<?php

namespace Tests\Unit\Notifications\User;

use App\Models\User;
use App\Notifications\User\PasswordReset;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    public function testToMailReturnsMailable()
    {
        $notification = new PasswordReset('token');

        $user = factory(User::class)->create();

        $this->assertInstanceOf(MailMessage::class, $notification->toMail($user));
    }
}
