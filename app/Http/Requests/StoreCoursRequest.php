<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // À ajuster selon vos besoins d'autorisation
    }

    public function rules(): array
    {
        return [
            'niveau_id' => ['required', 'integer', 'exists:niveaux,id'],
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ordre' => ['required', 'integer', 'min:1'],
            'statut' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'niveau_id.required' => 'Le niveau est requis',
            'niveau_id.exists' => 'Le niveau sélectionné n\'existe pas',
            'titre.required' => 'Le titre est requis',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'ordre.required' => 'L\'ordre est requis',
            'ordre.min' => 'L\'ordre doit être supérieur à 0',
        ];
    }
}
