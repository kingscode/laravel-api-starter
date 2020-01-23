<?php

declare(strict_types=1);

namespace App\Listeners\User;

use App\Events\User\Deleting;

final class CleanUpWhenDeleting
{
    public function handle(Deleting $deleting)
    {
        $user = $deleting->getUser();

        $user->tokens()->delete();
    }
}
