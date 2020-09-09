<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications\User\Email;

use App\Models\User;
use App\Notifications\User\Email\CantUpdate;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;
use function in_array;

final class CantUpdateTest extends TestCase
{
    public function testToMailReturnsMailMessage()
    {
        $notification = new CantUpdate();

        $user = User::factory()->createOne();

        $this->assertInstanceOf(MailMessage::class, $notification->toMail($user));
    }

    public function testViaReturnsMailChannel()
    {
        $notification = new CantUpdate();

        $this->assertTrue(in_array('mail', $notification->via()));
    }
}
