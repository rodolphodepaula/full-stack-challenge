<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:50',
            'code' => 'required|string|min:3|max:20',
            'status' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo Nome é obrigatório.',
            'name.string' => 'O campo Nome deve ser uma string.',
            'name.min' => 'O campo Nome deve ter pelo menos :min caracteres.',
            'name.max' => 'O campo Nome não pode ter mais de :max caracteres.',
            'code.required' => 'O campo Code é obrigatório.',
            'code.string' => 'O campo Code deve ser uma string.',
            'code.min' => 'O campo Code deve ter pelo menos :min caracteres.',
            'code.max' => 'O campo Code não pode ter mais de :max caracteres.',
            'status.required' => 'O campo Status é obrigatório.',
            'status.boolean' => 'O campo Status deve ser verdadeiro (1) ou falso (0).',
        ];
    }
}
