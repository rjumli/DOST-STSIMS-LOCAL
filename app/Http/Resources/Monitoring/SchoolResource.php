<?php

namespace App\Http\Resources\Monitoring;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $campus = ($this->is_main == 0) ? '- '.$this->campus : ''; 
        return [
            'id' => $this->id,
            'selected' => false,
            'name' => ucwords(strtolower($this->school->name.' '.$campus)),
            'class' => $this->school->class->name,
            'campus' => $this->campus,
            'is_main' => $this->is_main,
            'semesters' => (count($this->semesters)>0) ? $this->semesters[0] : '',
            // 'academic_year' => (count($this->semesters)>0) ? $this->semesters[0]->academic_year.' - '.$this->semesters[0]->semester->name : '-',
            'semester' => (count($this->semesters)>0) ? $this->semesters[0]->semester->name : '-',
            'start' => (count($this->semesters)>0) ? date('F Y', strtotime($this->semesters[0]->start_at)) : '-',
            'end' => (count($this->semesters)>0) ? date('F Y', strtotime($this->semesters[0]->end_at)) : '-',
            'status' => (count($this->semesters)>0) ? true : false,
        ];
    }
}
