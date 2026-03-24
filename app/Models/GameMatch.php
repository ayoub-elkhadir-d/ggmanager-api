<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMatch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
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

    /**
     * Get the tournament that this match belongs to.
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the first player in the match.
     */
    public function player1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    /**
     * Get the second player in the match.
     */
    public function player2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    /**
     * Get the winner of the match.
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    /**
     * Get the next match in the tournament bracket.
     */
    public function nextMatch(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'next_match_id');
    }

    /**
     * Get the previous matches that lead to this match.
     */
    public function previousMatches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'next_match_id');
    }
}
