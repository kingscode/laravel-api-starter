<?php

declare(strict_types=1);

namespace App\Notifications\User;

use App\Mail\User\PasswordReset as PasswordResetMail;
use App\Models\User;
use Illuminate\Notifications\Notification;

final class PasswordReset extends Notification
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $user): PasswordResetMail
    {
        return new PasswordResetMail($user, $this->token);
    }
}
