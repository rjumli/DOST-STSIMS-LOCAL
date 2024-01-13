<?php

namespace App\Services\School;

use Hashids\Hashids;
use App\Models\Scholar;
use App\Models\SchoolCampus;
use App\Models\SchoolSemester;
use App\Http\Resources\School\ListsResource;
use App\Http\Resources\School\IndexResource;
use App\Http\Resources\School\SemestersResource;

class ViewService
{
    public function fetch($request){
        $data = ListsResource::collection(
            SchoolCampus::query()
            ->with('school.class','term:id,name','grading:id,name')
            ->with('region:region,code','province:name,code','municipality:name,code')
            ->when($request->region, function ($query, $region) {
                $query->where('region_code',$region);
            })
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
        // $total = Scholar::whereHas('education',function ($query) use ($id) {
        //     $query->where('school_id',$id);
        // })->count();

        $total = 0;
        // $graduating = Scholar::whereHas('education',function ($query) use ($id) {
        //     $query->where('school_id',$id);
        // })->whereHas('status',function ($query) use ($id) {
        //     $query->where('name','Graduated');
        // })
        // ->count();
        $graduating = 0;
        // $ongoing = Scholar::whereHas('education',function ($query) use ($id) {
        //     $query->where('school_id',$id);
        // })->whereHas('status',function ($query) use ($id) {
        //     $query->where('type','Ongoing');
        // })->count();
        $ongoing = 0;
        $array = [
            ['counts' => $total, 'name' => 'Total Scholars', 'icon' => 'ri-group-2-line', 'color' => 'success'],
            ['counts' => $graduating,'name' => 'Total Graduates', 'icon' => 'bx bxs-graduation', 'color' => 'info'],
            ['counts' => $ongoing,'name' => 'Ongoing Scholars', 'icon' => 'ri-account-circle-line', 'color' => 'primary']
        ];
        return $array;
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
            // ->orderBy('start_at','DESC')
            ->orderBy('semester_id','DESC')
            ->paginate($counts)
            ->withQueryString()
        );

        return $data;
    }
}
