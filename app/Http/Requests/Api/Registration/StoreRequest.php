<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Registration;

use Illuminate\Foundation\Http\FormRequest;

final class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc,dns',],
        ];
    }
}
