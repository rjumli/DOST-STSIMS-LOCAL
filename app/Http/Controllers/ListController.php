<?php

namespace App\Http\Controllers;

use App\Models\ListCourse;
use App\Models\ListAgency;
use App\Models\ListDropdown;
use App\Models\ListPrivilege;
use App\Models\ListProgram;
use App\Models\ListStatus;
use App\Models\LocationRegion;
use App\Models\LocationProvince;
use App\Models\LocationMunicipality;
use App\Models\LocationBarangay;
use App\Models\SchoolCampus;
use App\Models\SchoolCourse;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\Dropdown\LocationResource;
use App\Http\Resources\Dropdown\SchoolResource;
use App\Http\Resources\Dropdown\CourseResource;
use App\Http\Resources\Dropdown\SubcourseResource;
use App\Http\Resources\Dropdown\CourseListResource;
// use App\Http\Resources\School\Search\SchoolResource;

class ListController extends Controller
{
    public $id;

    public function __construct()
    {
        $this->id = config('app.agency');
    }

    public function regions(){
        $data = LocationRegion::orderBy('id','ASC')->get();
        return DefaultResource::collection($data);
    }

    public function provinces($id = null){
        $data = LocationProvince::where('region_code',$id)->orderBy('name','ASC')->get();
        return LocationResource::collection($data);
    }

    public function municipalities($id = null){
        $data = LocationMunicipality::where('province_code',$id)->orderBy('name','ASC')->get();
        return LocationResource::collection($data);
    }

    public function barangays($id = null){
        $data = LocationBarangay::where('municipality_code',$id)->orderBy('name','ASC')->get();
        return LocationResource::collection($data);
    }

    public function schools(Request $request){
        $keyword = $request->input('word');
        $is_endorsed = $request->input('is_endorsed');
        $code = ListAgency::where('id',$this->id)->value('region_code');
        // dd($is_endorsed);
        $data = SchoolCampus::with('school','term')->with('courses.course')
        ->where(function ($query) use ($code,$is_endorsed) {
            ($is_endorsed) ? $query->where('assigned_region','!=',$code) : $query->where('assigned_region',$code);
        })
        ->whereHas('school',function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        })
        ->orWhere(function ($query) use ($keyword) {
            $query->where('campus',$keyword);
        })
        ->get()->take(10);

        return SchoolResource::collection($data);
    }

    public function courses(Request $request){
        $id = $request->id;
        $data = SchoolCourse::whereHas('school',function ($query) use ($id) {
            $query->where('id',$id);
        })
        ->get();
        return CourseResource::collection($data);
    }

    public function subcourses($school,$course){
        $data = SchoolCourse::where('school_id',$school)->where('course_id',$course)->get();
        return SubcourseResource::collection($data);
    }

    public function course_list(Request $request){
        $keyword = $request->input('word');
        $data = ListCourse::where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        })
        ->get()->take(10);
        return CourseListResource::collection($data);
    }

    public function api_agencies(){
        $data = ListAgency::all();
        return $data;
    }

    public function api_dropdowns(){
        $data = ListDropdown::all();
        return $data;
    }

    public function api_privileges(){
        $data = ListPrivilege::all();
        return $data;
    }

    public function api_programs(){
        $data = ListProgram::all();
        return $data;
    }

    public function api_statuses(){
        $data = ListStatus::all();
        return $data;
    }

    public function api_location($type)
    {   
        switch($type){
            case 'regions' :
                $data = LocationRegion::get();
            break;
            case 'provinces' :
                $data = LocationProvince::get();
            break;
            case 'municipalities' :
                $data = LocationMunicipality::get();
            break;
            case 'barangays' :
                $data = LocationBarangay::get();
            break;
        }
        return $data;
    }
}
