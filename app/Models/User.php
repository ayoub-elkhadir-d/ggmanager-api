<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];


    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class, 'organizer_id');
    }


    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }


    public function player1Matches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'player1_id');
    }


    public function player2Matches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'player2_id');
    }


    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }


    public function isPlayer(): bool
    {
        return $this->role === 'player';
    }
}
