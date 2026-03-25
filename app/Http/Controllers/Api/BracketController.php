<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BracketResource;
use App\Models\Tournament;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BracketController extends Controller
{

    public function show(int $id): JsonResponse
    {
        $tournament = Tournament::query()
            ->with([
                'matches' => fn($q) => $q
                    ->orderBy('round_number')
                    ->orderBy('match_order'),

                'matches.player1:id,name,username',
                'matches.player2:id,name,username',
                'matches.winner:id,name,username',
            ])
            ->findOrFail($id);

        if ($tournament->status === 'open') {
            return response()->json([
                'message' => 'Le bracket n\'a pas encore été généré. Les inscriptions sont toujours ouvertes.',
            ], 422);
        }

        if ($tournament->matches->isEmpty()) {
            return response()->json([
                'message' => 'Aucun match trouvé pour ce tournoi.',
            ], 404);
        }

        return response()->json(new BracketResource($tournament));
    }
}
