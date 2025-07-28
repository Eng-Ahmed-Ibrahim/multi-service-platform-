<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "title"=>$this->title,
            "latitude"=>$this->latitude,
            "longitude"=>$this->longitude,
            "name_en"=>$this->name_en,
            "name_ar"=>$this->name_ar,
        ];
    }
}
