<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications\User\Email;

use App\Mail\User\Email\CantUpdate as CantUpdateMail;
use App\Notifications\User\Email\CantUpdate;
use Database\Factories\UserFactory;
use Tests\TestCase;
use function in_array;

final class CantUpdateTest extends TestCase
{
    public function testToMailReturnsMail()
    {
        $notification = new CantUpdate();

        $user = UserFactory::new()->createOne();

        $this->assertInstanceOf(CantUpdateMail::class, $notification->toMail($user));
    }

    public function testViaReturnsMailChannel()
    {
        $notification = new CantUpdate();

        $this->assertTrue(in_array('mail', $notification->via()));
    }
}
