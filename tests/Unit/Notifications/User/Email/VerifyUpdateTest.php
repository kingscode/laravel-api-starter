<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications\User\Email;

use App\Models\User;
use App\Notifications\User\Email\VerifyUpdate;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;
use function in_array;

final class VerifyUpdateTest extends TestCase
{
    public function testToMailReturnsMailMessage()
    {
        $notification = new VerifyUpdate('token');

        $user = factory(User::class)->create();

        $this->assertInstanceOf(MailMessage::class, $notification->toMail($user));
    }

    public function testViaReturnsMailChannel()
    {
        $notification = new VerifyUpdate('token');

        $this->assertTrue(in_array('mail', $notification->via()));
    }
}
