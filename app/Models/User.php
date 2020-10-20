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

    private ?UserToken $currentToken = null;

    public function tokens(): HasMany
    {
        return $this->hasMany(UserToken::class);
    }

    public function setCurrentToken(UserToken $token)
    {
        $this->currentToken = $token;
    }

    public function getCurrentToken()
    {
        return $this->currentToken;
    }

    public function getEmail(): string
    {
        return $this->getAttributeValue('email');
    }

    public function getName(): string
    {
        return $this->getAttributeValue('name');
    }
}
