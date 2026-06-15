<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'trip_date_id',
        'assigned_by',
        'assigned_at',
        'status',
        'notes'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function tripDate()
    {
        return $this->belongsTo(TripDate::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
