<?php

namespace App\Http\Resources\Dropdown;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $name = ucwords(strtolower($this->school->name));
        // $campus = ($this->is_main) ? ' - Main' : ' - '.ucwords(strtolower($this->campus));

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
            'value' => $this->id,
            'name' => $name.$campus,
            'term' => $this->term->name,
            'region' => $this->assigned_region
        ];
    }
}
