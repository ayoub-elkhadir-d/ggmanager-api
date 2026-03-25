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
            return response()->json(['message' => 'You are not allowed to do this.'], 403);
        }

        if ($tournament->status !== 'open')
            return response()->json(['message' => 'Only open tournaments can be closed.'], 400);

        $tournament->update(['status' => 'closed']);

        return response()->json(['message' => 'Tournament has been closed successfully.'], 200);
    }
}
