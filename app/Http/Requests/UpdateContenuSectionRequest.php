<?php

namespace App\Http\Requests;

use App\Enums\ContentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateContenuSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section_id' => ['sometimes', 'exists:sections_cours,id'],
            'titre' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'contenu' => ['sometimes', 'string'],
            'type_contenu' => ['sometimes', new Enum(ContentType::class)],
            'url_contenu' => ['nullable', 'string', 'url'],
            'duree' => ['sometimes', 'integer', 'min:0'],
            'ordre' => ['sometimes', 'integer', 'min:1'],
            'statut' => ['sometimes', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'section_id.exists' => 'La section sélectionnée n\'existe pas',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'url_contenu.url' => 'L\'URL du contenu n\'est pas valide',
            'duree.min' => 'La durée doit être supérieure ou égale à 0',
            'ordre.min' => 'L\'ordre doit être supérieur à 0'
        ];
    }
}
