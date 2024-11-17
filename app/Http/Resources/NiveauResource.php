<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NiveauResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'description' => $this->description,
            'ordre' => $this->ordre,
            'cours' => CoursResource::collection($this->whenLoaded('cours')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
