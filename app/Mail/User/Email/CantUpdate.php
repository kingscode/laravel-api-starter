<?php

declare(strict_types=1);

namespace App\Mail\User\Email;

use App\Models\User;
use Illuminate\Mail\Mailable;

final class CantUpdate extends Mailable
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this
            ->subject('Email address update request')
            ->to($this->user->getEmail(), $this->user->getName())
            ->markdown('mail.user.email.cant_update')
            ->with([
                'user' => $this->user,
            ]);
    }
}
