<?php

namespace App\Services\Monitoring;

use App\Models\Setting;
use App\Models\ListStatus;
use App\Models\SchoolCampus;
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

    public function statistics($request){
        return [
            'statistics' => [
                'count_status' => $this->statuses(),
                'count_checking' => $this->checking($request),
                'count_released' => $this->released(),
                'terms' => [
                    'semester' => SchoolCampus::where('term_id',4)->where('region_code',$this->code)->count(),
                    'trimester' => SchoolCampus::where('term_id',5)->where('region_code',$this->code)->count(),
                    'quarter' => SchoolCampus::where('term_id',6)->where('region_code',$this->code)->count(),
                ]
            ]
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
}
