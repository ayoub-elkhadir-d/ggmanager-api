<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BracketResource;
use App\Models\Tournament;
use Illuminate\Http\JsonResponse;

class BracketController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $tournament = Tournament::query()
            ->with([
                'matches' => fn($q) => $q
                    ->orderBy('round_number')
                    ->orderBy('match_position'),

                'matches.player1:id,name',
                'matches.player2:id,name',
                'matches.winner:id,name',
            ])
            ->findOrFail($id);

        if ($tournament->status === 'open') {
            return $this->validationError(
                ['status' => ['Le bracket n\'a pas encore été généré. Les inscriptions sont toujours ouvertes.']],
                'Le bracket n\'a pas encore été généré. Les inscriptions sont toujours ouvertes.'
            );
        }

        if ($tournament->matches->isEmpty()) {
            return $this->notFound('Aucun match trouvé pour ce tournoi.');
        }

        return $this->success(new BracketResource($tournament));
    }
}
