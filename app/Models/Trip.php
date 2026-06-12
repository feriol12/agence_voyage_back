<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'title',
        'reference',
        'description',
        'duration_days',
        'capacity',
        'base_price',
        'status',
        'is_active'
    ];

    /*
    |----------------------------
    | RELATIONS
    |----------------------------
    */

    // Un trip appartient à une destination
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    // Un trip peut avoir plusieurs dates
    public function tripDates()
    {
        return $this->hasMany(TripDate::class);
    }

    // Un trip peut être assigné à plusieurs clients
    public function clientTrips()
    {
        return $this->hasMany(ClientTrip::class);
    }

    /*
    |----------------------------
    | SCOPES (optionnel mais pro)
    |----------------------------
    */

    // Trips actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Trips disponibles
    public function scopeAvailable($query)
    {
        return $query->where('status', 'disponible');
    }
}
