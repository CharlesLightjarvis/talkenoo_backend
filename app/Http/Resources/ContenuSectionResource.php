<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContenuSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'section_id' => $this->section_id,
            'titre' => $this->titre,
            'description' => $this->description,
            'contenu' => $this->contenu,
            'type_contenu' => $this->type_contenu,
            'url_contenu' => $this->url_contenu,
            'duree' => $this->duree,
            'ordre' => $this->ordre,
            'statut' => $this->statut,
            'section' => new SectionCoursResource($this->whenLoaded('section')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
