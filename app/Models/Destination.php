<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
      protected $fillable = [
        'name', 'country', 'continent', 'description', 
        'image_url', 'visa_required', 'is_active'
    ];

    protected $casts = [
        'visa_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    // public function trips(): HasMany
    // {
    //     return $this->hasMany(Trip::class);
    // }
}
