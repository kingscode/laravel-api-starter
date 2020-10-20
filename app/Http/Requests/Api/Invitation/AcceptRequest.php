<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Invitation;

use Illuminate\Foundation\Http\FormRequest;

final class AcceptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token'    => ['required', 'string'],
            'email'    => ['required', 'string', 'email:rfc,dns',],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
        ];
    }
}
