<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected bool $includePrivate = false;

    public function includePrivate(): self
    {
        $this->includePrivate = true;

        return $this;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username ?? null,
            'email' => $this->when($this->includePrivate, $this->email),
            'role' => $this->when($this->includePrivate, $this->role),
        ];
    }
}
