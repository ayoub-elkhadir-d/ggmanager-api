<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organizer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('game');
            $table->dateTime('date');

            $table->integer('max_participants');


            $table->enum('status', [
                'open',
                'closed',
                'in_progress',
                'finished'
            ])->default('open');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
