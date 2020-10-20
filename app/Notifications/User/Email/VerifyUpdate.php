<?php

declare(strict_types=1);

namespace App\Notifications\User\Email;

use App\Mail\User\Email\VerifyUpdate as VerifyUpdateMail;
use App\Models\User;
use Illuminate\Notifications\Notification;

final class VerifyUpdate extends Notification
{
    public string $token;

    private string $email;

    public function __construct(string $token, string $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $user): VerifyUpdateMail
    {
        return new VerifyUpdateMail($user, $this->token, $this->email);
    }
}
