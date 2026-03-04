<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'string', 'in:client,gestionnaire'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'email' => 'Le format de l\'adresse e-mail est invalide.',
            'max' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
            'confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'unique' => 'Cette adresse e-mail est déjà utilisée.',
            'in' => 'Le rôle sélectionné est invalide.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nom',
            'email' => 'adresse e-mail',
            'password' => 'mot de passe',
            'role' => 'rôle',
        ];
    }
}
