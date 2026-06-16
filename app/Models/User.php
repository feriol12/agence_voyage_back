<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ✅ AJOUTE CETTE MÉTHODE ICI
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this->email));
    }

    // Relations pour les voyages du client (uniquement pour les non-admins)
    public function clientTrips()
    {
        return $this->hasMany(ClientTrip::class, 'user_id');
    }

    // Les voyages auxquels le client est inscrit
    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'client_trips', 'user_id', 'trip_id')
            ->withPivot('trip_date_id', 'status', 'notes', 'assigned_by', 'assigned_at')
            ->withTimestamps();
    }
}
