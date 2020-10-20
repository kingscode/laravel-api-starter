<?php

declare(strict_types=1);

namespace App\Notifications\User\Email;

use App\Mail\User\Email\CantUpdate as CantUpdateMail;
use App\Models\User;
use Illuminate\Notifications\Notification;

final class CantUpdate extends Notification
{
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $user): CantUpdateMail
    {
        return new CantUpdateMail($user);
    }
}
