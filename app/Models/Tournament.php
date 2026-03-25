<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'name',
        'game',
        'date',
        'max_participants',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
        'max_participants' => 'integer',
    ];


    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }


    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }


    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class);
    }
}
