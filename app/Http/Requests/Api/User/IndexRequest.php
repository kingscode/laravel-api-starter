<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

final class IndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => ['sometimes', 'string'],
            'email'    => ['sometimes', 'string'],
            'sort_by'  => ['sometimes', 'string', 'in:name,email'],
            'desc'     => ['sometimes', 'boolean'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
