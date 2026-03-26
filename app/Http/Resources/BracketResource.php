<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BracketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $rounds = $this->matches
            ->sortBy(['round_number', 'match_order'])
            ->groupBy('round_number')
            ->map(fn($matches, $roundNumber) => [
                'round'   => (int) $roundNumber,
                'label'   => $this->resolveRoundLabel((int) $roundNumber, $this->total_rounds),
                'matches' => MatchResource::collection($matches),
            ])
            ->values();

        return [
            'tournament' => [
                'id'           => $this->id,
                'name'         => $this->name,
                'game'         => $this->game,
                'status'       => $this->status,
                'total_rounds' => $this->total_rounds,
            ],
            'bracket' => $rounds,
        ];
    }

    private function resolveRoundLabel(int $round, int $totalRounds): string
    {
        $diff = $totalRounds - $round;

        return match ($diff) {
            0 => 'Final',
            1 => 'Semi-Final',
            2 => 'Quarter-Final',
            default => "Round {$round}",
        };
    }
}
