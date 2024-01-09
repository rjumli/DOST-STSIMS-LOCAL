<?php

namespace App\Services\School;

use App\Models\SchoolProspectus;

class UpdateService
{
    public function prospectus($request){
        $data = SchoolProspectus::where('id',$request->id)->update($request->except('id','type','subtype'));
        return $data;
    }
}
