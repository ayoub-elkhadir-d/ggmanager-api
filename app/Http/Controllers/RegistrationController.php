<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegistrationResource;
use App\Models\Tournament;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Request $request, Tournament $tournament)
    {

        $user = $request->user();

        if ($user->role !== 'player') {
            return $this->forbidden('Only players can register for tournaments.');
        }

        if ($tournament->status !== 'open') {
            return $this->error('This tournament is not open for registration.', 400);
        }

        if ($tournament->registrations()->count() >= $tournament->max_participants) {
            return $this->error('This tournament is full.', 400);
        }

        if ($tournament->registrations()->where('user_id', $user->id)->exists()) {
            return $this->error('You are already registered for this tournament.', 400);
        }

        $registration = $tournament->registrations()->create([
            'user_id' => $user->id,
        ]);

        $registration->load('user');

        return $this->success(
            new RegistrationResource($registration),
            'Successfully registered for the tournament.',
            201
        );
    }
}
