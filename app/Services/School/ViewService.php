<?php

namespace App\Services\School;

use Hashids\Hashids;
use App\Models\Scholar;
use App\Models\ListAgency;
use App\Models\SchoolCourse;
use App\Models\SchoolCampus;
use App\Models\SchoolSemester;
use App\Http\Resources\School\ListsResource;
use App\Http\Resources\School\IndexResource;
use App\Http\Resources\School\CoursesResource;
use App\Http\Resources\School\SemestersResource;

class ViewService
{
    public $region,$acronym;

    public function __construct()
    {
        $id = config('app.agency');
        $this->region = ListAgency::where('id',$id)->value('region_code');
        $this->acronym = ListAgency::where('id',$id)->with('region')->first();
    }

    public function lists($request){
        $data = ListsResource::collection(
            SchoolCampus::query()
            ->with('school.class','term:id,name','grading:id,name')
            ->with('region:region,code','province:name,code','municipality:name,code')
            ->when($request->keyword, function ($query, $keyword) {
                $query->whereHas('school',function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', '%'.$keyword.'%');
                })->orWhere(function ($query) use ($keyword) {
                    $query->where('campus', 'LIKE', '%'.$keyword.'%');
                });
            })
            ->whereHas('school',function ($query) {
                $query->orderBy('name','ASC');
            })
            ->where('assigned_region',$this->region)
            ->paginate($request->counts)
            ->withQueryString()
        );
        return $data;
    }

    public function view($id){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($id);
        
        $school = new IndexResource(
            SchoolCampus::with('school')
            ->with('gradings')
            ->with('school.class','term:id,name','grading:id,name')
            ->with('semesters.semester','courses.course')
            ->with('region:region,code','province:name,code','municipality:name,code')
            ->where('id',$id[0])->first()
        );
        $semester = SchoolSemester::where('is_active',1)->where('school_id',$id[0])->first();
   
        $array = [
            'school' => $school,
            'active' => ($semester) ? new SemestersResource($semester) : ''
        ];
        return $array;
    }

    public function counts($id){
        $total = Scholar::whereHas('education',function ($query) use ($id) {
            $query->where('school_id',$id);
        })->count();

        $graduating = Scholar::whereHas('education',function ($query) use ($id) {
            $query->where('school_id',$id);
        })->whereHas('status',function ($query) use ($id) {
            $query->where('name','Graduated');
        })
        ->count();
        $ongoing = Scholar::whereHas('education',function ($query) use ($id) {
            $query->where('school_id',$id);
        })->whereHas('status',function ($query) use ($id) {
            $query->where('type','Ongoing');
        })->count();
        $array = [
            ['counts' => $ongoing,'name' => 'Ongoing Scholars', 'icon' => 'ri-account-circle-line', 'color' => 'primary'],
            ['counts' => $total, 'name' => 'Total Scholars', 'icon' => 'ri-group-2-line', 'color' => 'success'],
            ['counts' => $graduating,'name' => 'Total Graduates', 'icon' => 'bx bxs-graduation', 'color' => 'info'],
        ];
        return $array;
    }

    public function statistics(){
        $statistics = [
            'inside' => [
                'total' => SchoolCampus::where('assigned_region',$this->region)->count(),
                'types' => $this->types('inside')
            ],
            'outside' => [
                'total' => SchoolCampus::where('assigned_region','!=',$this->region)->count(),
                'types' => $this->types()
            ],
            'name' => $this->acronym,
            'active' =>  SchoolSemester::where('is_active',1)->count()
        ];
        return $array = ['statistics' => $statistics];
    }

    public function types($type = null){
        $types = [
            SchoolCampus::when($type, function ($query) {
                $query->where('assigned_region', $this->region);
            }, function ($query) { $query->where('assigned_region', '!=', $this->region);
            })->whereHas('school', function ($query) {$query->where('class_id', 3);})->count(),
            SchoolCampus::when($type, function ($query) {
                $query->where('assigned_region', $this->region);
            }, function ($query) { $query->where('assigned_region', '!=', $this->region);
            })->whereHas('school', function ($query) {$query->where('class_id', 2);})->count()
        ];
        return $types;
    }

    public static function courses($request){
        $id = $request->id;
        $counts = $request->counts;
        $data = CoursesResource::collection(
            SchoolCourse::query()
            ->when($request->keyword, function ($query, $keyword) {
                $query->whereHas('course',function ($query) use ($keyword) {
                    $query->where('name','LIKE', '%'.$keyword.'%');
                });
            })
            ->with('course','prospectuses')
            ->where('school_id',$id)
            ->orderBy('created_at','DESC')
            ->paginate($counts)
            ->withQueryString()
        );
        return $data;
    }

    public static function semesters($request){
        $id = $request->id;
        $keyword = $request->keyword;
        $counts = $request->counts;

        $data = SemestersResource::collection(
            SchoolSemester::query()
            ->with('semester')
            ->where('school_id',$id)
            ->orderBy('year','DESC')
            ->orderBy('semester_id','DESC')
            ->paginate($counts)
            ->withQueryString()
        );
        return $data;
    }
}
