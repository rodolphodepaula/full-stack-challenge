<?php
namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'enrollment' => 'sometimes',
            'status' => 'required|boolean',
            'company_uuid' => 'required|uuid',
            'password' => [
                'required',
                'min:6',
                'max:20',
                'confirmed',
                Rules\Password::defaults()
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo Nome é obrigatório.',
            'name.string' => 'O campo Nome deve ser uma string.',
            'name.min' => 'O campo Nome deve ter pelo menos :min caracteres.',
            'name.max' => 'O campo Nome não pode ter mais de :max caracteres.',
            'email.required' => 'O campo E-mail é obrigatório.',
            'email.string' => 'O campo E-mail deve ser uma string.',
            'email.lowercase' => 'O campo E-mail deve estar em letras minúsculas.',
            'email.email' => 'O campo E-mail deve ser um endereço de e-mail válido.',
            'email.max' => 'O campo E-mail não pode ter mais de :max caracteres.',
            'email.unique' => 'O E-mail fornecido já está em uso.',
            'status.required' => 'O campo Status é obrigatório.',
            'status.boolean' => 'O campo Status deve ser verdadeiro (1) ou falso (0).',
            'company_uuid.required' => 'O campo Empresa é obrigatório.',
            'company_uuid.uuid' => 'O campo Empresa deve ser um UUID válido.',
            'password.required' => 'O campo Senha é obrigatório.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.max' => 'A senha não pode ter mais de :max caracteres.',
        ];
    }
}
