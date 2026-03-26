<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'game'             => $this->game,
            'date'             => $this->date,
            'max_participants' => $this->max_participants,
            'status'           => $this->status,
            'total_rounds'     => $this->total_rounds,

            'organizer' => $this->whenLoaded('organizer', fn() => [
                'id'   => $this->organizer->id,
                'name' => $this->organizer->name,
            ]),

            'registrations' => $this->whenLoaded(
                'registrations',
                fn() => $this->registrations->map(fn($r) => [
                    'user_id' => $r->user->id,
                    'name'    => $r->user->name,
                ])
            ),

            'matches' => $this->whenLoaded(
                'matches',
                fn() => MatchResource::collection($this->matches)
            ),

            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
