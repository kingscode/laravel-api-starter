<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications\User\Email;

use App\Mail\User\Email\VerifyUpdate as VerifyUpdateMail;
use App\Notifications\User\Email\VerifyUpdate;
use Database\Factories\UserFactory;
use Tests\TestCase;
use function in_array;

final class VerifyUpdateTest extends TestCase
{
    public function testToMailReturnsMail()
    {
        $notification = new VerifyUpdate('token', 'info@kingscode.nl');

        $user = UserFactory::new()->createOne();

        $this->assertInstanceOf(VerifyUpdateMail::class, $notification->toMail($user));
    }

    public function testViaReturnsMailChannel()
    {
        $notification = new VerifyUpdate('token', 'info@kingscode.nl');

        $this->assertTrue(in_array('mail', $notification->via()));
    }
}
