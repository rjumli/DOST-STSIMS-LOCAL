<?php

namespace App\Services\Monitoring;

use App\Models\Scholar;
use App\Http\Resources\Monitoring\LackingResource;

class ListService
{
    public function lacking_grades($request){
        $scholars = Scholar::select('id','spas_id','awarded_year','status_id')
        ->with('profile:id,scholar_id,firstname,lastname,middlename')
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
}
