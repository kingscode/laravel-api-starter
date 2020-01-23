<?php

declare(strict_types=1);

namespace App\Events\User;

use App\Models\User;

final class Deleting
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
