<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class UserToken extends Model
{
    protected $fillable = [
        'token',
    ];
}
