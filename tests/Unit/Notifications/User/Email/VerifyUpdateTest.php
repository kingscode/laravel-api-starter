<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications\User\Email;

use App\Notifications\User\Email\VerifyUpdate;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;
use function in_array;

final class VerifyUpdateTest extends TestCase
{
    public function testToMailReturnsMailMessage()
    {
        $notification = new VerifyUpdate('token');

        $user = UserFactory::new()->createOne();

        $this->assertInstanceOf(MailMessage::class, $notification->toMail($user));
    }

    public function testViaReturnsMailChannel()
    {
        $notification = new VerifyUpdate('token');

        $this->assertTrue(in_array('mail', $notification->via()));
    }
}
