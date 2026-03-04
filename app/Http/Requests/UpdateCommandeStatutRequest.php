<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommandeStatutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'statut' => ['required', 'in:en_attente,en_preparation,prete,annulee'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Le statut est obligatoire.',
            'in' => 'Le statut sélectionné est invalide.',
        ];
    }
}
