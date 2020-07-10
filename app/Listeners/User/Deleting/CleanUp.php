<?php

declare(strict_types=1);

namespace App\Listeners\User\Deleting;

use App\Events\User\Deleting;

final class CleanUp
{
    public function handle(Deleting $deleting)
    {
        $user = $deleting->getUser();

        $user->tokens()->delete();
    }
}
