<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpotResource extends JsonResource
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
            'name' => $this->nombre,
            'latitude' => $this->lat,
            'longitude' => $this->lon,
            'description' => $this->descripcion,
            'level' => $this->nivel,
            'image' => $this->imagen,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
