<?php

namespace App\Services\School;

use App\Models\SchoolProspectus;

class UpdateService
{
    public function prospectus($request){
        $data = SchoolProspectus::where('id',$request->id)->update($request->except('id','type','editable'));
        $data = SchoolProspectus::where('id',$request->id)->first();
        return $data;
    }

    public static function lock($request){
        $data = SchoolProspectus::where('id',$request->id)->update(['is_locked' => $request->is_locked]);
        $data = SchoolProspectus::where('id',$request->id)->first();
        return $data;
    }

    public static function status($request){
        $data = SchoolProspectus::where('id',$request->id)->update(['is_active' => $request->is_active]);
        $data = SchoolProspectus::where('id',$request->id)->first();

        $update = SchoolProspectus::where('id','!=',$request->id)->where('school_course_id',$data->school_course_id)->update(['is_active' => 0]);
        return $data;
    }
}
