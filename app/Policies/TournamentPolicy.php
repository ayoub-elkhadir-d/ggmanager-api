<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tournament;

class TournamentPolicy
{
    public function update(User $user, Tournament $tournament): bool
    {
        if ($user->role === 'organizer' && $user->id === $tournament->organizer_id)
            return true;

        return false;
    }

    public function delete(User $user, Tournament $tournament): bool
    {
        if ($user->role === 'organizer' && $user->id === $tournament->organizer_id && $tournament->status === 'open')
            return true;

        return false;
    }
}
