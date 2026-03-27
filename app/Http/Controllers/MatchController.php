<?php

namespace App\Http\Controllers;

use App\Events\ScoreUpdated;
use App\Http\Resources\MatchResource;
use App\Models\GameMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tournament_id'   => 'required|integer|exists:tournaments,id',
            'round_number'    => 'required|integer|min:1',
            'match_position'  => 'required|integer|min:1',
            'player1_id'      => 'nullable|integer|exists:users,id',
            'player2_id'      => 'nullable|integer|exists:users,id',
            'next_match_id'   => 'nullable|integer|exists:matches,id',
        ]);

        $match = GameMatch::create($request->only([
            'tournament_id', 'round_number', 'match_position',
            'player1_id', 'player2_id', 'next_match_id',
        ]));

        return response()->json($match, 201);
    }

    public function submitScore(Request $request, GameMatch $match)
    {
        if ($match->status === 'finished') {
            return $this->conflict('Match already finished');
        }

        $request->validate([
            'player1_score' => 'required|integer|min:0',
            'player2_score' => 'required|integer|min:0',
            'winner_id'     => 'required|integer|in:' . $match->player1_id . ',' . $match->player2_id,
        ]);

        $winnerId = $request->winner_id;

        if ($request->player1_score === $request->player2_score) {
            return $this->validationError([
                'player1_score' => ['Scores must be distinct to determine a winner'],
                'player2_score' => ['Scores must be distinct to determine a winner'],
            ], 'Scores must be distinct to determine a winner');
        }

        DB::transaction(function () use ($match, $request, $winnerId) {
            $match->update([
                'player1_score' => $request->player1_score,
                'player2_score' => $request->player2_score,
                'winner_id'     => $winnerId,
                'status'        => 'finished',
            ]);

            if ($match->next_match_id) {
                $nextMatch = GameMatch::find($match->next_match_id);

                if (is_null($nextMatch->player1_id)) {
                    $nextMatch->player1_id = $winnerId;
                } else {
                    $nextMatch->player2_id = $winnerId;
                }

                $nextMatch->save();
            }
        });

        $match = $match->fresh()->load([
            'player1:id,name',
            'player2:id,name',
            'winner:id,name',
        ]);

        broadcast(new ScoreUpdated($match))->toOthers();

        return $this->success(new MatchResource($match), 'Score submitted');
    }
}
