<?php

namespace App\Services\Settings;

use App\Models\Setting;
use App\Models\SchoolSemester;
use App\Models\ScholarEnrollment;

class SaveService
{
    public function year($request){
        $data = Setting::first();
        $data->update(['year' => $request->year,'academic_year' => $request->academic_year,'semester_id' => NULL,'trimester_id' => NULL,'quarter_id' => NULL]);
        if($data){
            $ids = SchoolSemester::where('is_active',1)->pluck('id');
            foreach($ids as $id){
                $scholar = ScholarEnrollment::where('is_enrolled',0)->where('semester_id',$id)->update(['is_missed' => 1]);
            }
            SchoolSemester::query()->where('is_active',1)->update(['is_active' => 0]);
        }
    }

    public function semester($request){
        $data = Setting::first();
        if($request->type == 'Semester'){
            $data->semester_id = $request->semester;
        }else if($request->type == 'Trimester'){
            $data->trimester_id = $request->semester;
        }else{
            $data->quarter_id = $request->semester;
        }
        $data->save();
        $data = Setting::first();
        return $data;
    }
}
