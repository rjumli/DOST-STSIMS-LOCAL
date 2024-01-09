<?php

namespace App\Services\School;

use App\Models\ListDropdown;
use App\Models\SchoolGrading;
use App\Models\SchoolSemester;
use App\Models\SchoolProspectus;

class SaveService
{
    public function prospectus($request){
        $level = ListDropdown::where('classification','Level')->select('name','others')->get();
        $years = $request->years;
        $term = $request->term;
        $semesters = ListDropdown::where('classification',$term)->pluck('name');

        $group = []; $courses = [];

        for ($i = 0; $i < $years; ++$i) {
            $sem = [];
            foreach($semesters as $key=>$semester){
                $sem[] = ['semester' => $semester,'total' => 0,'courses' => $courses];
            }
            $group[] = [
                'year_level' => $level[$i]['others'],
                'year_ordinal' => $level[$i]['name'],
                'semesters' => $sem
            ];
        }
        $request['subjects'] = json_encode($group,true);
        $request['added_by'] = \Auth::user()->id;

        $data = SchoolProspectus::create($request->all());
        return $data;
    }

    public function grading($request){
        $data = SchoolGrading::create($request->all());
        return $data;
    }

    public static function semester($request){
        $data = SchoolSemester::create(array_merge($request->all(),['is_active' => true]));
        return $data;
    }

}
