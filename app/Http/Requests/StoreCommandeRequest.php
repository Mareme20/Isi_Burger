<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'burger_id' => ['nullable', 'exists:burgers,id'],
            'quantite' => ['nullable', 'integer', 'min:1'],
            'items' => ['nullable', 'array'],
            'items.*' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'integer' => 'Le champ :attribute doit être un entier.',
            'min' => 'La :attribute doit être au moins de :min.',
            'exists' => 'Le burger sélectionné est invalide.',
            'items.array' => 'Le format de la commande est invalide.',
        ];
    }

    public function attributes(): array
    {
        return [
            'burger_id' => 'burger',
            'quantite' => 'quantité',
            'items' => 'articles',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasSingle = $this->filled('burger_id') && $this->filled('quantite');
            $hasItems = collect((array) $this->input('items', []))
                ->filter(fn ($qte) => (int) $qte > 0)
                ->isNotEmpty();

            if (! $hasSingle && ! $hasItems) {
                $validator->errors()->add('items', 'Ajoutez au moins un burger avec une quantité supérieure à 0.');
            }
        });
    }
}
