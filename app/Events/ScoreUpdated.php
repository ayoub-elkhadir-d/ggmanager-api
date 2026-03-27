<?php

namespace App\Events;

use App\Http\Resources\MatchResource;
use App\Models\GameMatch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class ScoreUpdated implements ShouldBroadcastNow
{
    use SerializesModels;

    public function __construct(public GameMatch $match) {}

    public function broadcastOn(): Channel
    {
        return new Channel('tournament.' . $this->match->tournament_id);
    }

    public function broadcastWith(): array
    {
        return ['match' => new MatchResource($this->match)];
    }
}
