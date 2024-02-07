<?php

namespace App\Services\Monitoring;

use App\Models\Scholar;
use App\Models\Setting;
use App\Models\ListStatus;
use App\Models\ListDropdown;
use App\Models\SchoolCampus;
use App\Models\ScholarEnrollment;
use App\Http\Resources\Dropdown\ListResource;
use App\Http\Resources\Dropdown\StatusResource;
use App\Http\Resources\Monitoring\SchoolResource;

class ViewService
{
    public $code;

    public function __construct()
    {
        $setting = Setting::first();
        $this->code = $setting->agency->region_code;
    }

    public function schools(){
        $data = SchoolCampus::with('school.class')->with(['semesters' => function ($query) {
            $query->with('semester')->where('is_active', 1)->first();
        }])->whereHas('scholars', function ($query) {
            $query->whereHas('scholar', function ($query) {
                $query->whereHas('status', function ($query) {
                    $query->where('type', 'Ongoing');
                });
            });
        })->where('is_active',1)->where('assigned_region',$this->code)->get();
        return ['schools' => SchoolResource::collection($data)];
    }

    public function batches($request){
        $ongoingYears = Scholar::whereHas('status', function ($query) {
            $query->where('type', 'Ongoing');
        })
        ->pluck('awarded_year')
        ->unique()
        ->sort()
        ->values()
        ->all();

        $year = $ongoingYears[0];
        return [
            'years' => $ongoingYears, 
            'dropdowns' => [
                'statuses' => StatusResource::collection(ListStatus::get()),
                'lists' => ListResource::collection(ListDropdown::all()),
            ],
            'statistics' => $this->statistics($request,$ongoingYears)
        ];
    }

    public function statuses(){
        $statuses = ListStatus::select('id','name','color','type')->where('type','ongoing')->withCount('status')->orderBy('status_count', 'desc')->get();
        $substatuses = ListStatus::select('id','name','color','type')->where('is_active',1)->where('type','Status')->withCount('status')->get();
    
        return [
            'statuses' => $statuses,
            'substatuses' => $substatuses
        ];
    }

    public function checking(){
        return [];
    }

    public function released(){
        return [];
    }

    public function years($years){
        $statuses = ListStatus::whereIn('type',['Progress','Ongoing'])->get();
        $levels = ListDropdown::where('classification','Level')->get();

        $array = []; $sums = []; $total = [];
        
        foreach($years as $key=>$year){
            $count = [];
            foreach($levels as $key2=>$level){
                $l = $level->id;
                $data = Scholar::where('awarded_year',$year)
                ->whereHas('education',function ($query) use ($l){
                    $query->where('level_id',$l);
                })
                ->count();
                array_push($count,$data);    
                $sums[$key2][$key] = $data;
            }

            $array[] = [
                'status' => $year,
                'count' => $count,
                'total' => array_sum($count)
            ];
        }

        foreach($levels as $key2=>$level){
            $total[] = array_sum($sums[$key2]); 
        }

        $total2 = [
            'status' => 'Total',
            'count' => $total,
            'total' => array_sum($total)
        ];
        
        $all = [
            'statuses' => $array,
            'totals' => $total2,
            'types' => $levels
        ];
        return $all;
    }

    public function statistics($request,$ongoingYears){
        $year = ($ongoingYears) ? $ongoingYears[0] : $request->year;
        return [
            'year' => $year,
            'ongoing' => ScholarEnrollment::where('is_enrolled',1)
            ->whereHas('scholar',function ($query) use ($year){
                $query->whereHas('status', function ($query) use ($year){
                    $query->where('type', 'Ongoing');
                })->where('awarded_year',$year);
            })
            ->whereHas('semester',function ($query){
                $query->where('is_active',1);
            })->count(),
            'subtotal' => Scholar::whereHas('status', function ($query) {
                $query->where('type', 'Ongoing');
            })->where('awarded_year',$year)->count(),
            'total' => Scholar::where('awarded_year',$year)->count(),
            'statuses' => ListStatus::select('id','name','color','type')
            ->where('type','ongoing')
            ->withCount(['scholars' => function ($query) use ($year) {
                $query->where('awarded_year', $year);
            }])
            ->orderBy('scholars_count', 'desc')->get()
        ];
    }
}
