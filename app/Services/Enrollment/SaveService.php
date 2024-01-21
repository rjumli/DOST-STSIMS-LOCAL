<?php

namespace App\Services\Enrollment;

use Hashids\Hashids;
use App\Models\ScholarEducation;
use App\Models\ScholarEnrollment;
use App\Models\ScholarEnrollmentSubject;
use App\Http\Resources\Enrollment\EducationResource;
use App\Http\Resources\Enrollment\EnrollmentResource;

class SaveService
{   
    public function enrollment($request){
        $data = ScholarEnrollment::where('id',$request->id)->first();
        $attach = $this->upload($data,$request,'enrollments');
        $data->attachment = $attach;
        $data->added_by = \Auth::user()->id;
        $data->is_enrolled = 1;
        if($data->save()){
            $lists = json_decode($request->lists,true);
            foreach($lists as $key=>$list){
                $options = [];
                ScholarEnrollmentSubject::create(array_merge($list,[
                    'options' => json_encode($options) ,
                    'enrollment_id' => $request->id,
                    'encoded_by' =>  \Auth::user()->id
                ]));
            }
        }
        $data = ScholarEnrollment::with('semester.semester','level','subjects')->where('id',$request->id)->first();
        return new EnrollmentResource($data);
    }

    public function grade($request){
        $data = ScholarEnrollment::with('semester.semester','level','subjects')->where('id',$request->id)->first();
        $attach = $this->upload($data,$request,'grades');
        $data->attachment = $attach;
        if($data->save()){
            $count = 0;
            $subjects = json_decode($request->lists,true);
            foreach($subjects as $list){
                $enrollment_id = $list['enrollment_id'];
                ($list['grade'] == null) ? $count++ : '';
                $data = ScholarEnrollmentSubject::where('id',$list['id'])->first();
                $data->grade = $list['grade'];
                $data->is_failed = ($list['grade']== 'F' || $list['grade'] == 5) ? 1 : 0;
                if($data->isDirty('grade')){
                    $data->encoded_by = \Auth::user()->id;
                }
                $data->save();
            }
            $data = ScholarEnrollment::where('id',$request->id)->first();
            ($count == 0) ? $data->is_grades_completed = 1 : $data->is_grades_completed = 0;
            $data->save();

            $data = ScholarEnrollment::with('semester.semester','level','subjects')->where('id',$request->id)->first();
            return new EnrollmentResource($data);
        }
    }

    public function upload($data,$request,$type){
        $attach = json_decode($data->attachment,true);
        if($type == 'grades'){
            $count = count($attach['grades']);
            $name = 'Grades_'.$data->level->name.' '.$data->semester->semester->name.' '.$data->semester->academic_year;
        }else{
            $count = count($attach['enrollments']);
            $name = 'Enrollments_'.$data->level->name.' '.$data->semester->semester->name.' '.$data->semester->academic_year;
        }
        $hashids = new Hashids('krad',10);
        $code = $hashids->encode($data->scholar_id);
        
        if($request->hasFile('files'))
        {   
            $files = $request->file('files');   
            foreach ($files as $file) {
                if($count == 0){
                    $file_name = strtolower($name).'.'.$file->getClientOriginalExtension();
                }else{
                    $file_name = strtolower($name).'-'.$count.'.'.$file->getClientOriginalExtension();
                    $count++;
                }
                $file_path = ($type == 'grades') ? $file->storeAs('uploads/'.$code.'/Grades', $file_name, 'public') : $file->storeAs('uploads/'.$code.'/Enrollments', $file_name, 'public');

                $attachment[] = [
                    'name' => $file_name,
                    'file' => $file_path,
                    'added_by' => \Auth::user()->id,
                    'created_at' => date('M d, Y g:i a', strtotime(now()))
                ];
            }
            if($type == 'grades'){
                $attach['grades'][] = $attachment;
            }else{
                $attach['enrollments'][] = $attachment;
            }
            return $attach;
        }
    }

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

    public function lock($request){
        if($request->is_locked){
            return $this->unlockGrade($request);
        }else{
            return $this->lockGrade($request);
        }
    }

    public function unlockGrade($request){
        $data = ScholarEnrollment::with('semester.semester','level','subjects')->where('id',$request->id)->first();
        $data->update($request->except('type'));
        return new EnrollmentResource($data);
    }

    public function lockGrade($request){
        $enrollment_id = $request->id;
        $data = ScholarEnrollment::with('semester.semester','level','subjects')->where('id',$enrollment_id)->first();
        $data->update($request->except('type'));
        if($data->is_grades_completed){
            $scholar_id =  $data->scholar_id;
            $semester_id =  $data->semester_id;

            $p = ScholarEducation::with('subcourse')->where('scholar_id',$scholar_id)->first();
            $years = $p->subcourse->years; 
            $semesters = 3;

            $prospectus = json_decode($p->information,true);

            for($x = 0; $x < $years; $x++){
                for($y = 0; $y < $semesters; $y++){
                    foreach($request->lists as $list){
                        $code = $list['code']; $is_lab = $list['is_lab'];
                        $results = array_filter($prospectus['prospectus'][$x]['semesters'][$y]['courses'], function($people) use ($code,$is_lab) {
                            if(array_key_exists("code",$people)){
                                return $people['code'] == $code && $people['is_lab'] == $is_lab;
                            }else{
                                return $results = [];
                            }
                        });
                        if(!empty($results)){
                            $key = array_keys($results)[0];
                            if($list['grade'] != null){
                                ($list['grade'] == 'F' || $list['grade'] == 'f' || $list['grade'] == 5) ? $failed = 1 : $failed = 0;
                                if(array_key_exists("is_failed",$prospectus['prospectus'][$x]['semesters'][$y]['courses'][$key])){
                                    if($prospectus['prospectus'][$x]['semesters'][$y]['courses'][$key]['is_failed']){
                                        $grades = $prospectus['prospectus'][$x]['semesters'][$y]['courses'][$key]['grades'];
                                        array_push($grades,strtoupper($list['grade']));
                                        $prospectus['prospectus'][$x]['semesters'][$y]['courses'][$key]['grades'] = $grades;
                                    }
                                }else{
                                    if($failed){
                                        $grades = [];
                                        array_push($grades,strtoupper($list['grade']));
                                        $prospectus['prospectus'][$x]['semesters'][$y]['courses'][$key]['grades'] = $grades;
                                    }else{
                                        $prospectus['prospectus'][$x]['semesters'][$y]['courses'][$key]['grade'] = $list['grade'];
                                    }
                                }
                                $prospectus['prospectus'][$x]['semesters'][$y]['courses'][$key]['is_failed'] = $failed;
                            }
                        }
                    }
                }
            }
            $p->information = $prospectus;
            $p->save();   
        }
        return new EnrollmentResource($data);
    }
}
