<?php

namespace App\Http\Controllers;

use App\Http\Resources\TournamentResource;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{

    public function index()
    {
        $tournaments = Tournament::with('organizer:id,name')->get();
        return $this->success(TournamentResource::collection($tournaments));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'game' => 'required|string|max:255',
            'date' => 'required|date',
            'max_participants' => 'required|integer|min:2',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $tournament = Tournament::create([
            'organizer_id' => Auth::id(),
            'name' => $request->name,
            'game' => $request->game,
            'date' => $request->date,
            'max_participants' => $request->max_participants,
            'status' => 'open',
        ]);

        $tournament->load('organizer:id,name');

        return $this->success(new TournamentResource($tournament), 'Tournament created', 201);
    }


    public function show(Tournament $tournament)
    {
        $tournament->load([
            'organizer:id,name',
            'registrations.user:id,name',
            'matches.player1:id,name',
            'matches.player2:id,name',
            'matches.winner:id,name',
        ]);

        return $this->success(new TournamentResource($tournament));
    }


    public function update(Request $request, Tournament $tournament)
    {
        if ($tournament->organizer_id !== Auth::id()) {
            return $this->forbidden('Unauthorized');
        }

        if ($this->hasMatchesStarted($tournament)) {
            return $this->forbidden('Cannot modify a tournament if matches have started');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'game' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'max_participants' => 'sometimes|integer|min:2',
            'status' => 'sometimes|string|in:open,closed',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $tournament->update($request->only([
            'name',
            'game',
            'date',
            'max_participants',
            'status'
        ]));

        $tournament->load('organizer:id,name');

        return $this->success(new TournamentResource($tournament), 'Tournament updated');
    }


    public function destroy(Tournament $tournament)
    {
        if ($tournament->organizer_id !== Auth::id()) {
            return $this->forbidden('Unauthorized');
        }

        if ($this->hasMatchesStarted($tournament)) {
            return $this->forbidden('Cannot delete a tournament if matches have started');
        }

        $tournament->delete();

        return $this->success(null, 'Tournament deleted');
    }


    private function hasMatchesStarted(Tournament $tournament): bool
    {
        if (in_array($tournament->status, ['in_progress', 'finished'])) {
            return true;
        }

        $hasActiveMatches = $tournament->matches()->whereIn('status', ['in_progress', 'finished'])->exists();

        return $hasActiveMatches;
    }
}
