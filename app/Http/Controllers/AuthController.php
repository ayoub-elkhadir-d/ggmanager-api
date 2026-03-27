<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:player,organizer'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'player',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => (new UserResource($user))->includePrivate(),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Utilisateur créé avec succès', 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->unauthorized('Les identifiants sont incorrects.');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => (new UserResource($user))->includePrivate(),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Connexion réussie');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Déconnexion réussie');
    }

    public function me(Request $request)
    {
        return $this->success((new UserResource($request->user()))->includePrivate());
    }
}
