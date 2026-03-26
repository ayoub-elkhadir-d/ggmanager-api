<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'round_number' => $this->round_number,
            'match_order'  => $this->match_order,
            'status'       => $this->status,

            'player1' => $this->whenLoaded('player1', fn() => [
                'id'       => $this->player1->id,
                'name'     => $this->player1->name,
                'username' => $this->player1->username ?? null,
            ]),

            'player2' => $this->whenLoaded('player2', fn() => [
                'id'       => $this->player2->id,
                'name'     => $this->player2->name,
                'username' => $this->player2->username ?? null,
            ]),

            'score_player1' => $this->score_player1,
            'score_player2' => $this->score_player2,

            'winner' => $this->whenLoaded(
                'winner',
                fn() => $this->winner
                    ? [
                        'id'       => $this->winner->id,
                        'name'     => $this->winner->name,
                        'username' => $this->winner->username ?? null,
                    ]
                    : null
            ),

            'played_at' => $this->played_at?->toIso8601String(),
        ];
    }
}
