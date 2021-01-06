<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Invitation;

use Illuminate\Foundation\Http\FormRequest;

final class ResendRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc,dns'],
        ];
    }
}
