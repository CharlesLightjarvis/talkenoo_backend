<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cours_id' => ['required', 'exists:cours,id'],
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ordre' => ['sometimes', 'integer', 'min:1'],
            'statut' => ['sometimes', 'boolean'],
            'duree_estimee' => ['sometimes', 'integer', 'min:0']
        ];
    }

    public function messages(): array
    {
        return [
            'cours_id.required' => 'Le cours est requis',
            'cours_id.exists' => 'Le cours sélectionné n\'existe pas',
            'titre.required' => 'Le titre est requis',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'ordre.min' => 'L\'ordre doit être supérieur à 0',
            'duree_estimee.min' => 'La durée estimée doit être supérieure ou égale à 0'
        ];
    }
}
