<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBurgerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prix' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:12288'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être un texte.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'integer' => 'Le champ :attribute doit être un entier.',
            'min' => 'Le champ :attribute doit être supérieur ou égal à :min.',
            'max.string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
            'max.file' => "Le fichier :attribute ne doit pas dépasser :max kilo-octets.",
            'image' => 'Le fichier :attribute doit être une image.',
            'mimes' => 'L\'image doit être au format :values.',
            'exists' => 'La valeur sélectionnée pour :attribute est invalide.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nom' => 'nom du burger',
            'prix' => 'prix',
            'description' => 'description',
            'image' => 'image',
            'stock' => 'stock',
            'category_id' => 'catégorie',
        ];
    }
}
