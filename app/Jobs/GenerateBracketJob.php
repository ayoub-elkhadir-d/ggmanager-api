<?php

namespace App\Jobs;

use App\Models\Tournament;
use App\Services\BracketService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateBracketJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Tournament $tournament) {}

    public function handle(): void
    {
        $service = new BracketService();
        $service->makeTree($this->tournament);
    }
}
