<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications\User;

use App\Mail\User\PasswordReset as PasswordResetMail;
use App\Notifications\User\PasswordReset;
use Database\Factories\UserFactory;
use Tests\TestCase;

final class PasswordResetTest extends TestCase
{
    public function testToMailReturnsMailMessage()
    {
        $notification = new PasswordReset('token');

        $user = UserFactory::new()->createOne();

        $this->assertInstanceOf(PasswordResetMail::class, $notification->toMail($user));
    }

    public function testViaReturnsMailChannel()
    {
        $notification = new PasswordReset('token');

        $this->assertTrue(in_array('mail', $notification->via()));
    }
}
