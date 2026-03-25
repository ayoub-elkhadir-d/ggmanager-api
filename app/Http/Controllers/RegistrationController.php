<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Request $request, Tournament $tournament)
    {
        $user = $request->user();

        if ($user->role !== 'player')
            return response()->json(['message' => 'Only players can register for tournaments.'], 403);

        if ($tournament->status !== 'open')
            return response()->json(['message' => 'This tournament is not open for registration.'], 400);

        if ($tournament->registrations()->count() >= $tournament->max_participants)
            return response()->json(['message' => 'This tournament is full.'], 400);

        if ($tournament->registrations()->where('user_id', $user->id)->exists())
            return response()->json(['message' => 'You are already registered for this tournament.'], 400);

        $registration = $tournament->registrations()->create([
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message'      => 'Successfully registered for the tournament.',
            'registration' => $registration,
        ], 201);
    }
}
