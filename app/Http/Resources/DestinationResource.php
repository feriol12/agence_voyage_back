<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class DestinationResource extends JsonResource
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
            'name' => $this->name,
            'country' => $this->country,
            'continent' => $this->continent,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'visa_required' => $this->visa_required,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
