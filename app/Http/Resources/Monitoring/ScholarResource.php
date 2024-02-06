<?php

namespace App\Http\Resources\Monitoring;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScholarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $campus = ($this->is_main == 0) ? '- '.$this->campus : '- Main'; 

        $name = ucwords(strtolower($this->education->school->school->name));
        
        if($this->education->school->is_main){
            if(!$this->education->school->is_alone){
                $campus = '';
            }else{
                $campus = '';
            }
        }else{
            if($this->education->school->is_alone){
                $campus = '';
            }else{
                $campus = ' - '.ucwords(strtolower($this->education->school->campus));
            }
        }

        $middlename = ($this->profile->middlename) ? $this->profile->middlename[0].'.' : ''; 

        return [
            'id' => $this->id,
            'spas_id' => $this->spas_id,
            'name' => $this->profile->lastname.', '.$this->profile->firstname.' '.$this->profile->suffix.' '.$middlename,
            'school' => $name.$campus,
            'course' => $this->education->course->name,
            'level' => ($this->education->level) ? $this->education->level->name : '-',
            'status' => $this->status,
            'selected' => false,
        ];
    }
}
