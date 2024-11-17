<?php

namespace App\Http\Requests;

use App\Enums\ContentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreContenuSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section_id' => ['required', 'exists:sections_cours,id'],
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'contenu' => ['nullable', 'string'],
            'type_contenu' => ['required', new Enum(ContentType::class)],
            'media_file' => [
                'required_unless:type_contenu,TEXTE',
                'file',
                'max:104857600' // 100MB
            ],
            'duree' => ['sometimes', 'integer', 'min:0'],
            'ordre' => ['sometimes', 'integer', 'min:1'],
            'statut' => ['sometimes', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'section_id.required' => 'La section est requise',
            'section_id.exists' => 'La section sélectionnée n\'existe pas',
            'titre.required' => 'Le titre est requis',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'type_contenu.required' => 'Le type de contenu est requis',
            'media_file.required_unless' => 'Le fichier est requis sauf pour le type TEXTE',
            'media_file.file' => 'Le fichier n\'est pas valide',
            'media_file.max' => 'Le fichier ne doit pas dépasser 100MB',
            'duree.min' => 'La durée doit être supérieure ou égale à 0',
            'ordre.min' => 'L\'ordre doit être supérieur à 0'
        ];
    }
}
