<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|min:3|max:50',
            'code' => 'sometimes|string|min:3|max:20',
            'status' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'O campo Nome deve ser uma string.',
            'name.min' => 'O campo Nome deve ter pelo menos :min caracteres.',
            'name.max' => 'O campo Nome não pode ter mais de :max caracteres.',
            'code.string' => 'O campo Code deve ser uma string.',
            'code.min' => 'O campo Code deve ter pelo menos :min caracteres.',
            'code.max' => 'O campo Code não pode ter mais de :max caracteres.',
            'status.boolean' => 'O campo Status deve ser verdadeiro (1) ou falso (0).',
        ];
    }
}
