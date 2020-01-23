<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\User\Deleting;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $dispatchesEvents = [
        'deleting' => Deleting::class,
    ];

    public function tokens(): HasMany
    {
        return $this->hasMany(UserToken::class);
    }
}
