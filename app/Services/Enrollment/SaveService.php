<?php

namespace App\Services\Enrollment;

use App\Models\ScholarEducation;
use App\Http\Resources\Enrollment\EducationResource;

class SaveService
{
    public function switch($request){
        $from = $request->from;
        $to = $request->to;
        $data = ScholarEducation::with('school.school','course','level','subcourse')->where('scholar_id',$request->scholar_id)->first();
        $years = $data->subcourse->years; 
        $semesters = 3;
        $prospectus = json_decode($data->information,true);
    
        for($x = 0; $x < $years; $x++){
            for($y = 0; $y < $semesters; $y++){
                $result = array_filter($prospectus['prospectus'][$x]['semesters'][$y]['courses'], function($all) use ($from) {
                    return $all['code'] == $from['code'];
                });
                if(!empty($result)){
                    for($z = 0; $z < $years; $z++){
                        for($w = 0; $w < $semesters; $w++){
                            $result2 = array_filter($prospectus['prospectus'][$z]['semesters'][$w]['courses'], function($all) use ($to) {
                                return $all['code'] == $to['code'] && $all['is_lab'] == $to['is_lab'];
                            });
                            if(!empty($result2)){
                                $key = array_keys($result)[0];
                                $key2 = array_keys($result2)[0];
                                $prospectus['prospectus'][$x]['semesters'][$y]['courses'][$key] = $result2[$key2];
                                $prospectus['prospectus'][$z]['semesters'][$w]['courses'][$key2] = $result[$key];
                                break;
                            }
                        }
                    }
                    break;
                }
            }
        }
        $data->information = $prospectus;
        if($data->save()){
            $res = ScholarEducation::with('school.school','course','level')->where('scholar_id',$request->scholar_id)->first();
            return new EducationResource($res);
        }
       
    }

}
