<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoursResource extends JsonResource
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
            'niveau_id' => $this->niveau_id,
            'titre' => $this->titre,
            'description' => $this->description,
            'ordre' => $this->ordre,
            'statut' => $this->statut,
            'niveau' => new NiveauResource($this->whenLoaded('niveau')),
            'sections' => SectionCoursResource::collection($this->whenLoaded('sections')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
