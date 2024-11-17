<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNiveauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // À ajuster selon vos besoins d'autorisation
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ordre' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du niveau est requis',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'ordre.required' => 'L\'ordre est requis',
            'ordre.min' => 'L\'ordre doit être supérieur à 0',
        ];
    }
}
