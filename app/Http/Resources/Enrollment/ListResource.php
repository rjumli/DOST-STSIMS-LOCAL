<?php

namespace App\Http\Resources\Enrollment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'is_enrolled' => $this->is_enrolled,
            'is_delayed' => $this->is_delayed,
            'is_missed' => $this->is_missed,
            'is_benefits_released' => $this->is_benefits_released,
            'is_grades_completed' => $this->is_grades_completed,
            'is_completed' => $this->is_completed,
            'attachment' => json_decode($this->attachment,true),
            'subjects' => $this->subjects,
            'benefits' => $this->benefits,
            'level' => $this->level->others,
            'semester' => $this->semester,
        ];
    }
}
