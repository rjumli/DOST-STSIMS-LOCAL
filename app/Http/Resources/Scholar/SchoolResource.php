<?php

namespace App\Http\Resources\Scholar;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $name = ucwords(strtolower($this->school->name));
        
        if($this->is_main){
            if(!$this->is_alone){
                $campus = '';
            }else{
                $campus = '';
            }
        }else{
            if($this->is_alone){
                $campus = '';
            }else{
                $campus = '-'.ucwords(strtolower($this->campus));
            }
        }

        return [
            'id' => $this->id,
            'scholar_id' => $this->scholar_id,
            'name' => $name.$campus,
            'class' => $this->school->class->name,
            'avatar' => $this->school->avatar,
            'shortcut' => $this->shortcut,
            'campus' => ($this->campus == 'MAIN') ?  'Main' : ucwords(strtolower($this->campus)),
            'address' => ucwords($this->address)
        ];
    }
}
