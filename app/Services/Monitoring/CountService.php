<?php

namespace App\Services\Monitoring;

use App\Models\Scholar;
use App\Models\ScholarEnrollment;

class CountService
{
    public function statistics(){
        $total_scholars = Scholar::count();
        return [
            'statistics' => [
                'right' => [
                    'total' => $total_scholars,
                    'ongoing' => $this->ongoing(),
                    'enrolled' => $this->enrolled(),
                ],
                'middle' => [

                ],
                'left' => [

                ]
            ]
        ];
    }

    public function ongoing(){
        $data = Scholar::whereHas('status',function ($query){
            $query->where('type','Ongoing');
        })->count();
        return $data;
    }

    public function enrolled(){
        $data = ScholarEnrollment::where('is_enrolled',1)
        ->whereHas('semester',function ($query){
            $query->where('is_active',1);
        })
        ->count();
    }

    public function schools(){
        $agency_id = config('app.agency');
        $region = ListAgency::select('region_code')->where('id',$agency_id)->first();
        $region = $region->region_code;

        $data = SchoolCampus::query()->whereHas('municipality',function ($query) use ($region){
            $query->whereHas('province',function ($query) use ($region){
                $query->whereHas('region',function ($query) use ($region){
                    $query->where('code',$region);
                });
            });
        })->count();
        return $data;
    }

    public function schools_active(){

    }
}
