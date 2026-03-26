<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'registered_at' => $this->registered_at?->toIso8601String(),
            'user' => $this->whenLoaded('user', fn() => new UseResource($this->user)),
        ];
    }
}
