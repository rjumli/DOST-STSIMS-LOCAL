<?php

namespace App\Services\Monitoring;

use App\Models\Scholar;
use App\Models\ScholarEnrollment;
use App\Http\Resources\Monitoring\ScholarResource;
use App\Http\Resources\Monitoring\LackingResource;
use App\Http\Resources\Monitoring\BenefitResource;
use App\Http\Resources\Monitoring\MissedResource;
use App\Http\Resources\Monitoring\TerminationResource;

class ListService
{
    public function lacking_grades($request){
        $keyword = $request->keyword;
        $scholars = Scholar::select('id','spas_id','awarded_year','status_id')
        ->withWhereHas('profile',function ($query) use ($keyword) {
            $query->select('id','scholar_id','firstname','lastname','middlename')
            ->when($keyword, function ($query, $keyword) {
                $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%'.$keyword.'%')
                ->where(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', '%'.$keyword.'%');
            });
        })
        ->withwhereHas('enrollments', function ($query){
            $query->select('id','scholar_id','level_id','semester_id')
                ->with('level','semester:id,semester_id,academic_year','semester.semester:id,name')
                ->withwhereHas('subjects',function ($query){
                    $query->where('grade',NULL);
                })->whereHas('semester',function ($query){
                    $query->where('is_active',0);
                });
        })
        ->withWhereHas('education', function ($query){
            $query->select('id','scholar_id','school_id','course_id')
            ->withWhereHas('school', function ($query){
                $query->select('id','school_id','campus')
                ->withWhereHas('school', function ($query){
                    $query->select('id','name','shortcut');
                })
                ->withWhereHas('gradings', function ($query){
                    $query->select('id','grade','school_id');
                });
            })->with('course');
        })
        ->withWhereHas('status',function ($query){
            $query->select('id','name','color')->where('type','ongoing');
        })
        ->paginate(5);
        return LackingResource::collection($scholars);
    }

    public function unreleased_benefits($request){
        $keyword = $request->keyword;
        $date = date('Y-m-d');
        $scholars = Scholar::select('id','spas_id','awarded_year','status_id')
        ->withWhereHas('profile',function ($query) use ($keyword) {
            $query->select('id','scholar_id','firstname','lastname','middlename')
            ->when($keyword, function ($query, $keyword) {
                $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%'.$keyword.'%')
                ->where(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', '%'.$keyword.'%');
            });
        })
        ->withwhereHas('enrollments', function ($query) use ($date){
            $query->select('id','scholar_id','level_id','semester_id')
            ->with('level','semester:id,semester_id,academic_year','semester.semester:id,name')
            ->withWhereHas('benefits',function ($query) use ($date){
                $query->whereIn('status_id',[11,12])->where('month','<=',$date);
            });
        })
        ->when($request->semester_id, function ($query, $semester) {
            $query ->whereHas('enrollments',function ($query, $semester){
                $query->where('semester_id',$semester);
            });
        })
        ->withWhereHas('status',function ($query){
            $query->select('id','name','color')->where('type','ongoing');
        })
        ->paginate(5);
        return BenefitResource::collection($scholars);
    }

    public function missed_enrollments($request){
        $keyword = $request->keyword;
        $scholars = Scholar::withWhereHas('status',function ($query){
            $query->where('type','ongoing');
        })
        ->withWhereHas('profile',function ($query) use ($keyword) {
            $query->select('id','scholar_id','firstname','lastname','middlename')
            ->when($keyword, function ($query, $keyword) {
                $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%'.$keyword.'%')
                ->where(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', '%'.$keyword.'%');
            });
        })
        ->withWhereHas('enrollments', function ($query) {
            $query->withWhereHas('semester',function ($query){
                $query->select('id','academic_year','year','semester_id')
                ->with('semester:id,name,others')
                ->where('is_active',0);
            })->withWhereHas('level',function ($query){
                $query->select('id','name','others');
            })
            ->where('is_enrolled',0);
        })
        ->paginate(5);
        return MissedResource::collection($scholars);
    }

    public function terminations($request){
        $data = ScholarEnrollment::select('id','scholar_id','semester_id','level_id')
        ->with('level:id,name,others')
        ->with('semester:id,academic_year,year,semester_id','semester.semester:id,name,others')
        ->with('scholar:id,spas_id','scholar.profile:scholar_id,firstname,middlename,lastname')
        ->with('scholar.status:id,name,color,others')
        ->with([
            'subjects' => function ($query) {
                $query->select('enrollment_id','code','subject','unit')
                ->where('is_failed', 1); // Include only 'lists' where is_failed is 1
            }
        ])
        ->withCount([
        'subjects' => function ($query) {
            $query->where('is_failed',1)->groupBy('enrollment_id');
        }])
        ->when($request->semester_id, function ($query, $semester) {
            $query->where('semester_id',$semester);
        })
        ->whereHas('scholar',function ($query){
            $query->whereHas('status',function ($query){
                $query->where('type','ongoing');
            });
        })
        ->where('is_checked',0)
        ->having('subjects_count','>', 1)
        ->paginate(5);

        return TerminationResource::collection($data);
    }

    public function batches($request){
        $year = $request->year;
        $data = Scholar::with('education.school.school','education.course','education.level','profile','status')
        ->whereHas('status',function ($query){
            $query->where('type','ongoing');
        })
        ->where('awarded_year',$year)->get()
        ->sortBy(function ($scholar) {
            return $scholar->profile->lastname;
        });
        return ScholarResource::collection($data);
    }
}
