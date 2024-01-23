<?php

namespace App\Services\Scholar;

use App\Models\Scholar;
use App\Models\ScholarEducation;
use App\Models\ScholarEnrollment;
use App\Models\ScholarProfile;
use App\Models\ScholarAddress;
use App\Http\Resources\Scholar\IndexResource;
use App\Http\Resources\Enrollment\ListResource;
use Laravel\Sanctum\PersonalAccessToken;


class ViewService
{
    public static function lists($request){
        $info = (!empty(json_decode($request->info))) ? json_decode($request->info) : NULL;
        $filter = (!empty(json_decode($request->subfilters))) ? json_decode($request->subfilters) : NULL;
        $keyword = $info->keyword;

        $data = IndexResource::collection(
            Scholar::
            with('addresses.region','addresses.province','addresses.municipality','addresses.barangay')
            ->with('profile')
            ->with('program:id,name','subprogram:id,name','category:id,name','status:id,name,type,color,others')
            ->with('education.school.school','education.course','education.level')
            ->whereHas('profile',function ($query) use ($keyword) {
                $query->when($keyword, function ($query, $keyword) {
                    $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%'.$keyword.'%')
                    ->where(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', '%'.$keyword.'%')
                    ->orWhere('spas_id','LIKE','%'.$keyword.'%');
                });
            })
            ->whereHas('addresses',function ($query) use ($filter) {
                if(!empty($filter)){
                    (property_exists($filter, 'region')) ? $query->where('region_code',$filter->region)->where('is_permanent',1) : '';
                    (property_exists($filter, 'province')) ? $query->where('province_code',$filter->province)->where('is_permanent',1) : '';
                    (property_exists($filter, 'municipality')) ? $query->where('municipality_code',$filter->municipality)->where('is_permanent',1) : '';
                    (property_exists($filter, 'barangay')) ? $query->where('barangay_code',$filter->barangay)->where('is_permanent',1) : '';
                }
            })
            ->whereHas('education',function ($query) use ($filter) {
                if(!empty($filter)){
                    (property_exists($filter, 'school')) ? $query->where('school_id',$filter->school) : '';
                    (property_exists($filter, 'course')) ? $query->where('course_id',$filter->course) : '';
                    (property_exists($filter, 'level')) ? $query->where('level_id',$filter->level) : '';
                }
            })
            ->where(function ($query) use ($info,$filter) {
                if(!empty($filter)){
                    (property_exists($filter, 'program')) ? $query->where('program_id',$filter->program) : '';
                    (property_exists($filter, 'subprogram')) ? $query->where('subprogram_id',$filter->subprogram) : '';
                    
                    // ($info->category == null) ? '' : $query->where('category_id',$info->category);
                    // ($info->status == null) ? '' : $query->where('status_id',$info->status);
                    // ($info->type == null) ? '' : $query->where('is_undergrad',$info->type);
                }
                if(!empty($info)){
                    ($info->year == null) ? '' : $query->where('awarded_year',$info->year);
                }
             })
            ->orderBy('awarded_year',$info->sort)
            ->paginate($info->counts)
            ->withQueryString()
        );

        return $data;
    }

    public function statistics(){
        $statistics = [
            Scholar::whereHas('status',function ($query) {
                $query->where('type','ongoing');
            })->count(),
            Scholar::whereHas('status',function ($query) {
                $query->where('name','Graduated');
            })->count(),
            Scholar::count()
        ];

        $types = [
            Scholar::where('is_undergrad',1)->count(),
            Scholar::where('is_undergrad',0)->count(),
        ];

        $scholar = Scholar::where('is_synced',0)->count();
        $profile = ScholarProfile::where('is_synced',0)->count();
        $address = ScholarAddress::where('is_synced',0)->count();
        $education = ScholarEducation::where('is_synced',0)->count();
        $total = $scholar + $profile + $address + $education;

        $array = [
            'total' => Scholar::count(),
            'ongoing' =>  Scholar::whereHas('status',function ($query) {
                $query->where('type','ongoing');
            })->count(),
            'statistics' => $statistics,
            'types' => $types,
            'sync_no' => $total
        ];
        return $array;
    }

    public function enrollments($request){
        $id = $request->id;
        $data = ScholarEnrollment::
        withWhereHas('semester', function ($query) {
            $query->select('id','academic_year','semester_id','start_at','end_at')
            ->withWhereHas('semester', function ($query) {
                $query->select('id','name');
            });
        })
        ->withWhereHas('level', function ($query) {
            $query->select('id','name','others');
        })
        // ->withWhereHas('lists', function ($query){
        //     $query->select('id','enrollment_id','code','subject','unit','grade','is_failed');
        // })
        ->withWhereHas('benefits', function ($query){
            $query->select('id','enrollment_id','amount','month','benefit_id','status_id')
            ->withWhereHas('benefit', function ($query){
                $query->select('id','name','type','short','regular_amount','summer_amount');
            })
            ->withWhereHas('status', function ($query){
                $query->select('id','name','color','others');
            });
        })
        ->where('scholar_id',$id)
        ->orderBy('id','DESC')
        ->get();
        return ListResource::collection($data);
    }

    public function api($request){
        $bearer = $request->bearerToken();
        $token = PersonalAccessToken::findToken($bearer);
        $region = $token->tokenable->profile->agency->region_code;
       
        $data = Scholar::with('address')->with('education')->with('profile')
        ->whereHas('education',function ($query) use ($region) {
            $query->whereHas('school',function ($query) use ($region) {
                $query->where('assigned_region',$region); 
            });
        })
        ->get();
        return $data;
    }
}
