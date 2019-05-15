<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'  => ['required', 'max:191', 'string'],
            'email' => ['required', 'max:191', 'email', Rule::unique('users', 'email')->ignore($this->user())],
        ];
    }
}
