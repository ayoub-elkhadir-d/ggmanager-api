<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'round_number' => $this->round_number,
            'match_order'  => $this->match_position,
            'status'       => $this->status,

            'player1' => $this->whenLoaded('player1', fn() => new UserResource($this->player1)),

            'player2' => $this->whenLoaded('player2', fn() => new UserResource($this->player2)),

            'player1_score' => $this->player1_score,
            'player2_score' => $this->player2_score,

            'winner' => $this->whenLoaded(
                'winner',
                fn() => $this->winner ? new UseResource($this->winner) : null
            ),

            'played_at' => $this->played_at?->toIso8601String(),
        ];
    }
}
