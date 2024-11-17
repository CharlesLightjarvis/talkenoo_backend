<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionCoursResource extends JsonResource
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
            'cours_id' => $this->cours_id,
            'titre' => $this->titre,
            'description' => $this->description,
            'ordre' => $this->ordre,
            'statut' => $this->statut,
            'duree_estimee' => $this->duree_estimee,
            'cours' => new CoursResource($this->whenLoaded('cours')),
            'contenus' => ContenuSectionResource::collection($this->whenLoaded('contenus')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
