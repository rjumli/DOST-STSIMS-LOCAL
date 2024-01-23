<?php

namespace App\Http\Resources\Benefit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_no' => ($this->profile->account_no == null) ? 'n/a' : $this->profile->account_no,
            'name' => $this->profile->lastname.', '. $this->profile->firstname,
            'program' => $this->program->name,
            'avatar' => 'avatar.jpg',
            'total' => $this->enrollments->flatMap->benefits->sum('amount'),
            'enrollments' => EnrollmentResource::collection($this->enrollments)
        ];
    }
}
