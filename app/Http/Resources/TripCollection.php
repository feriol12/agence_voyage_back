<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TripCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => TripResource::collection($this->collection),
        ];
    }
}
