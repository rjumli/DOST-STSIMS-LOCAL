<?php

namespace App\Http\Resources\Scholar;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $name = $this->profile->lastname.', '.$this->profile->firstname;
        $middlename = ($this->profile->middlename) ? $this->profile->middlename[0].'.' : '';
        return [
            'id' => $this->id,
            'awarded_year' => $this->awarded_year,
            'name' => $name.' '.$middlename,
            'program' => $this->program->name,
            'status' => $this->status->name,
            'school' => $this->education->school->school->shortcut,
            'course' => $this->education->course->name,
            'level' => ($this->education->level) ? $this->education->level->name : '-',
            'award' => ($this->education->award) ? $this->education->award->name : '-',
        ];
    }
}
