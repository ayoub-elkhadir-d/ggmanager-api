<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function close(Request $request, Tournament $tournament)
    {
        $user = $request->user();

        if ($user->cannot('update', $tournament)) {
            return $this->forbidden('You are not allowed to do this.');
        }

        if ($tournament->status !== 'open') {
            return $this->error('Only open tournaments can be closed.', 400);
        }

        $tournament->update(['status' => 'closed']);

        return $this->success(null, 'Tournament has been closed successfully.');
    }
}
