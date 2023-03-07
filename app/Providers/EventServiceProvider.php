<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\User\Deleting;
use App\Listeners\User\Deleting\CleanUp;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Deleting::class => [
            CleanUp::class,
        ],
    ];
}
