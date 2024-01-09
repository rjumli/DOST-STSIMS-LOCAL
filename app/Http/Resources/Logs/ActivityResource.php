<?php

namespace App\Http\Resources\Logs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'log_name' => $this->log_name,
            'description' => $this->description,
            'event' => $this->event,
            'properties' => $this->properties,
            'name' => $this->causer->profile->firstname.' '. $this->causer->profile->lastname,
            'is_active' => $this->causer->is_active,
            'role' => $this->causer->role,
            'created_at' =>  date('M d, Y g:i a', strtotime($this->created_at)),
            'updated_at' =>  date('M d, Y g:i a', strtotime($this->updated_at)),
        ];
    }
}
