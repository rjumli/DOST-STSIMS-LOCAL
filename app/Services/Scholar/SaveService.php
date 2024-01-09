<?php

namespace App\Services\Scholar;

use App\Models\Scholar;
use App\Models\ScholarAddress;
use App\Models\ScholarProfile;
use App\Models\ScholarEducation;
use App\Http\Resources\Scholar\IndexResource;

class SaveService
{
    public static function create($request){
        $count = Scholar::count();
        $stsims_id = 'STSIMS-'.$request->awarded_year."-".str_pad(($count+1), 5, '0', STR_PAD_LEFT);  
        $scholar = Scholar::create(array_merge($request->all(), ['stsims_id' => $stsims_id,'is_synced' => 0]));
        if($scholar){
            $profile = $scholar->profile()->create(array_merge($request->all(),['information' => json_encode([]),'is_synced' => 0]));
            if($profile){
                $address = $scholar->addresses()->create(array_merge($request->all(),['information' => json_encode([]),'is_synced' => 0]));
                if($address){
                    $school = $scholar->education()->create(array_merge($request->all(),['information' => json_encode([]),'is_synced' => 0]));
                    if($school){
                        $data = Scholar::
                        with('addresses.region','addresses.province','addresses.municipality','addresses.barangay')
                        ->with('profile')
                        ->with('program:id,name','subprogram:id,name','category:id,name','status:id,name,type,color,others')
                        ->with('education.school.school','education.course','education.level')
                        ->where('id',$scholar->id)
                        ->first();

                        return new IndexResource($data);
                    }
                }
            }
        }
    }

    public static function update(){

    }
}
