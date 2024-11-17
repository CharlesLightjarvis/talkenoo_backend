<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // À ajuster selon vos besoins d'autorisation
    }

    public function rules(): array
    {
        return [
            'niveau_id' => ['sometimes', 'integer', 'exists:niveaux,id'],
            'titre' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ordre' => ['sometimes', 'integer', 'min:1'],
            'statut' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'niveau_id.exists' => 'Le niveau sélectionné n\'existe pas',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'ordre.min' => 'L\'ordre doit être supérieur à 0',
        ];
    }
}
