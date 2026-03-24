<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'tournament_id',
        'round_number',
        'match_position',
        'player1_id',
        'player2_id',
        'player1_score',
        'player2_score',
        'winner_id',
        'next_match_id',
        'status',
    ];

    protected $casts = [
        'round_number' => 'integer',
        'match_position' => 'integer',
        'player1_score' => 'integer',
        'player2_score' => 'integer',
    ];


    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }


    public function player1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player1_id');
    }


    public function player2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player2_id');
    }


    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }


    public function nextMatch(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'next_match_id');
    }


    public function previousMatches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'next_match_id');
    }
}
