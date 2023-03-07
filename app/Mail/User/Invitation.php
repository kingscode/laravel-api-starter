<?php

declare(strict_types=1);

namespace App\Mail\User;

use App\Models\User;
use App\SPA\UrlGenerator;
use Illuminate\Mail\Mailable;

final  class Invitation extends Mailable
{

    public function __construct(
        private readonly User $user,
        private readonly string $token
    ) {
    }

    public function build(UrlGenerator $urlGenerator): Mailable
    {
        $acceptationUrl = $urlGenerator->to(
            'invitation/accept/' . $this->token,
            [
                'email' => $this->user->getEmail(),
            ]
        );

        return $this
            ->subject('Account invitation')
            ->to(
                $this->user->getEmail(),
                $this->user->getName()
            )
            ->markdown('mail.user.invitation')
            ->with([
                'user'            => $this->user,
                'acceptation_url' => $acceptationUrl,
            ]);
    }
}
