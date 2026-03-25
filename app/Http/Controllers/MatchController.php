<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    public function submitScore(Request $request, GameMatch $match)
    {
        if ($match->status === 'finished') {
            return response()->json(['message' => 'Match already finished'], 409);
        }

        $request->validate([
            'player1_score' => 'required|integer|min:0',
            'player2_score' => 'required|integer|min:0',
            'winner_id'     => 'required|integer|in:' . $match->player1_id . ',' . $match->player2_id,
        ]);

        $winnerId = $request->winner_id;

        if ($request->player1_score === $request->player2_score) {
            return response()->json(['message' => 'Scores must be distinct to determine a winner'], 422);
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

        return response()->json($match->fresh());
    }
}
