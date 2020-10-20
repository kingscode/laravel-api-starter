<?php

declare(strict_types=1);

namespace App\Mail\User;

use App\Models\User;
use App\SPA\UrlGenerator;
use Illuminate\Mail\Mailable;

final class PasswordReset extends Mailable
{
    private User $user;

    private string $token;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build(UrlGenerator $urlGenerator)
    {
        $passwordResetUrl = $urlGenerator->to('password/reset/' . $this->token, [
            'email' => $this->user->getEmail(),
        ]);

        return $this
            ->subject('Password reset')
            ->to($this->user->getEmail(), $this->user->getName())
            ->markdown('mail.user.password_reset')
            ->with([
                'user'               => $this->user,
                'password_reset_url' => $passwordResetUrl,
            ]);
    }
}
