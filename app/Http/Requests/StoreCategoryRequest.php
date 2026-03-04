<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255', 'unique:categories,nom'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être un texte.',
            'max' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
            'unique' => 'Cette :attribute existe déjà.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nom' => 'catégorie',
        ];
    }
}
