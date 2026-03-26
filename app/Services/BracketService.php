<?php

namespace App\Services;

use App\Models\GameMatch;

class BracketService
{
    public function makeTree($tournament)
    {
        $playersList = $tournament->registrations()->pluck('user_id')->toArray();
        shuffle($playersList);

        $totalPlayers = count($playersList);

        $totalRounds = 0;
        $count = $totalPlayers;
        while ($count > 1)
        {
            $count = $count / 2;
            $totalRounds++;
        }

        $allRoundMatches = [];

        for ($round = $totalRounds; $round >= 1; $round--)
        {

            $matchesInThisRound = $totalPlayers / pow(2, $round);

            $allRoundMatches[$round] = [];

            for ($position = 1; $position <= $matchesInThisRound; $position++) {

                $nextMatchId = null;

                if ($round < $totalRounds) {
                    $parentPosition = ceil($position / 2);
                    $nextMatchId = $allRoundMatches[$round + 1][$parentPosition - 1]->id;
                }

                $newMatch = GameMatch::create([
                    'tournament_id'  => $tournament->id,
                    'round_number'   => $round,
                    'match_position' => $position,
                    'next_match_id'  => $nextMatchId,
                    'player1_id'     => null,
                    'player2_id'     => null,
                    'status'         => 'pending',
                ]);

                $allRoundMatches[$round][] = $newMatch;
            }
        }

        $playerIndex = 0;

        foreach ($allRoundMatches[1] as $firstRoundMatch) {
            $firstRoundMatch->update([
                'player1_id' => $playersList[$playerIndex],
                'player2_id' => $playersList[$playerIndex + 1],
            ]);

            $playerIndex += 2;
        }
    }
}
