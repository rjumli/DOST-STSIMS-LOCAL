<?php

namespace App\Http\Resources\Scholar;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'scholar_id' => $this->scholar_id,
            'name' => ucwords(strtolower($this->school->name)),
            'class' => $this->school->class->name,
            'avatar' => $this->school->avatar,
            'shortcut' => $this->shortcut,
            'campus' => ($this->campus == 'MAIN') ?  'Main' : ucwords(strtolower($this->campus)),
            'address' => ucwords($this->address)
        ];
    }
}
