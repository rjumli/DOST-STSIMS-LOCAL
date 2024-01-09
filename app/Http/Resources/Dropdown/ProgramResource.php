<?php

namespace App\Http\Resources\Dropdown;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->id, 
            'name' => $this->name,
            'others' => $this->others,
            'is_sub' => $this->is_sub,
            'is_active' => $this->is_active,
        ];
    }
}
