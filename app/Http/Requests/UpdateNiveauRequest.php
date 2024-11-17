<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNiveauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // À ajuster selon vos besoins d'autorisation
    }

    public function rules(): array
    {
        return [
            'nom' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ordre' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'ordre.min' => 'L\'ordre doit être supérieur à 0',
        ];
    }
}
