<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Attributes\UseResource;
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
            'date'             => $this->date?->toIso8601String(),
            'max_participants' => $this->max_participants,
            'status'           => $this->status,
            'total_rounds'     => $this->total_rounds,

            'organizer' => $this->whenLoaded('organizer', fn() => new UseResource($this->organizer)),

            'registrations' => $this->whenLoaded(
                'registrations',
                fn() => RegistrationResource::collection($this->registrations)
            ),

            'matches' => $this->whenLoaded(
                'matches',
                fn() => MatchResource::collection($this->matches)
            ),

            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
