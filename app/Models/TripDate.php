<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripDate extends Model
{
    //
     use HasFactory;
 protected $fillable = [
        'trip_id',
        'start_date',
        'end_date',
        'price',
        'places_available'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2'
    ];


    // Relation avec Trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

}
