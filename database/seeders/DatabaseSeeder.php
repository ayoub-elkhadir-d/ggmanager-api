<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tournament;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Generate an Organizer
        $organizer = User::create([
            'name' => 'John Organizer',
            'email' => 'organizer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'organizer',
        ]);

        // 2. Generate Players
        $player1 = User::create([
            'name' => 'Alice Player',
            'email' => 'player1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'player',
        ]);

        $player2 = User::create([
            'name' => 'Bob Player',
            'email' => 'player2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'player',
        ]);

        // 3. Generate sample Tournaments
        Tournament::create([
            'organizer_id' => $organizer->id,
            'name' => 'Summer Championship GG',
            'game' => 'League of Legends',
            'date' => now()->addDays(14),
            'max_participants' => 16,
            'status' => 'open',
        ]);

        Tournament::create([
            'organizer_id' => $organizer->id,
            'name' => 'Valorant Winter Cup',
            'game' => 'Valorant',
            'date' => now()->addDays(30),
            'max_participants' => 32,
            'status' => 'open',
        ]);
        
        $this->command->info('Database seeded successfully with Organizer, Players, and Tournaments!');
    }
}
