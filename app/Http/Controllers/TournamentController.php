<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{

    public function index()
    {
        $tournaments = Tournament::with('organizer:id,name')->get();
        return response()->json($tournaments);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'game' => 'required|string|max:255',
            'date' => 'required|date',
            'max_participants' => 'required|integer|min:2',
            'format' => 'string|in:single_elimination,round_robin',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tournament = Tournament::create([
            'organizer_id' => Auth::id(),
            'name' => $request->name,
            'game' => $request->game,
            'date' => $request->date,
            'max_participants' => $request->max_participants,
            'format' => $request->format ?? 'single_elimination',
            'status' => 'open',
        ]);

        return response()->json($tournament, 201);
    }


    public function show(Tournament $tournament)
    {
        $tournament->load(['organizer:id,name', 'registrations.user:id,name', 'matches']);
        return response()->json($tournament);
    }


    public function update(Request $request, Tournament $tournament)
    {
        if ($tournament->organizer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($this->hasMatchesStarted($tournament)) {
            return response()->json(['message' => 'Cannot modify a tournament if matches have started'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'game' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'max_participants' => 'sometimes|integer|min:2',
            'format' => 'sometimes|string|in:single_elimination,round_robin',
            'status' => 'sometimes|string|in:open,closed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tournament->update($request->only([
            'name', 'game', 'date', 'max_participants', 'format', 'status'
        ]));

        return response()->json($tournament);
    }


    public function destroy(Tournament $tournament)
    {
        if ($tournament->organizer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($this->hasMatchesStarted($tournament)) {
            return response()->json(['message' => 'Cannot delete a tournament if matches have started'], 403);
        }

        $tournament->delete();

        return response()->json(null, 204);
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
