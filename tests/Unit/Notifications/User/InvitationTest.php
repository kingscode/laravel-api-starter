<?php

namespace Tests\Unit\Notifications\User;

use App\Models\User;
use App\Notifications\User\Invitation;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    public function testToMailReturnsMailable()
    {
        $notification = new Invitation('token');

        $user = factory(User::class)->create();

        $this->assertInstanceOf(MailMessage::class, $notification->toMail($user));
    }
}
