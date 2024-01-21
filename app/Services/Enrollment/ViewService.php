<?php

namespace App\Services\Enrollment;

use Hashids\Hashids;
use App\Models\Scholar;
use App\Models\ScholarEnrollment;
use App\Models\SchoolProspectus;
use App\Http\Resources\Enrollment\ShowResource;

class ViewService
{
    public static function search($request){
        $keyword = $request->keyword;
        $data = Scholar::with('profile')
        ->with('program:id,name','status:id,name,type,color,others')
        ->with('education.school.school','education.school.semesters','education.school.gradings','education.course','education.level')
        ->with('enrollments.semester.semester','enrollments.level','enrollments.subjects')
        ->whereHas('status',function ($query){
            $query->where('type','Ongoing');
        })
        ->when($request->keyword, function ($query, $keyword) {
            $query->whereHas('profile',function ($query) use ($keyword) {
                $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%'.$keyword.'%')
                ->orWhere(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', '%'.$keyword.'%')
                ->orWhere('spas_id','LIKE','%'.$keyword.'%');
            });
        })->take(5)->get();
        return ShowResource::collection($data);
    }

    public static function activeprospectus($request){
        $data = SchoolProspectus::where('is_active',1)->where('school_course_id',$request->school_course_id)->first();
        return $data;
    }
}
