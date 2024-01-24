<?php

namespace App\Http\Resources\Monitoring;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'school' => $this->school->school->name,
            'campus' => $this->school->campus,
            'course' => $this->course->name,
            'gradings' => $this->school->gradings
        ];
    }
}
