<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

final class Dispense extends Model
{
    protected $fillable = [
        'key', 'token', 'expires_at',
    ];

    protected $dates = [
        'expires_at',
    ];

    public function getExpiresAt(): Carbon
    {
        return $this->getAttributeValue('expires_at');
    }

    public function getToken(): string
    {
        return $this->getAttributeValue('token');
    }
}
