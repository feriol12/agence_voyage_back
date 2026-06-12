<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'reference' => $this->reference,
            'duration_days' => $this->duration_days,
            'capacity' => $this->capacity,
            'base_price' => $this->base_price,
            'status' => $this->status,
            'destination' => [
                'id' => $this->destination?->id,
                'name' => $this->destination?->name,
            ],
        ];
    }
}
