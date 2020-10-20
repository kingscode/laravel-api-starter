<?php

declare(strict_types=1);

namespace App\Mail\User\Email;

use App\Models\User;
use App\SPA\UrlGenerator;
use Illuminate\Mail\Mailable;

final class VerifyUpdate extends Mailable
{
    private User $user;

    private string $token;

    private string $email;

    public function __construct(User $user, string $token, string $email)
    {
        $this->user = $user;
        $this->token = $token;
        $this->email = $email;
    }

    public function build(UrlGenerator $urlGenerator)
    {
        $verifyUrl = $urlGenerator->to('profile/email/verify') . "#token={$this->token}&email={$this->email}";

        return $this
            ->subject('Email address update request')
            ->to($this->user->getEmail(), $this->user->getName())
            ->markdown('mail.user.email.verify_update')
            ->with([
                'user'       => $this->user,
                'verify_url' => $verifyUrl,
            ]);
    }
}
