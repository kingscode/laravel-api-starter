<?php

declare(strict_types=1);

namespace App\Mail\User;

use App\Models\User;
use App\SPA\UrlGenerator;
use Illuminate\Mail\Mailable;

final class Invitation extends Mailable
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
        $acceptationUrl = $urlGenerator->to('invitation/accept/' . $this->token, [
            'email' => $this->user->getEmail(),
        ]);

        return $this
            ->subject('Account invitation')
            ->to($this->user->getEmail(), $this->user->getName())
            ->markdown('mail.user.invitation')
            ->with([
                'user'            => $this->user,
                'acceptation_url' => $acceptationUrl,
            ]);
    }
}
