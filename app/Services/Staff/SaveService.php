<?php

namespace App\Services\Staff;

use App\Models\User;
use App\Models\Setting;
use App\Models\UserProfile;
use App\Models\SchoolCampus;
use App\Models\LocationRegion;
use App\Models\LocationProvince;
use App\Traits\HandlesImage;
use App\Http\Resources\Staff\IndexResource;

class SaveService
{
    use HandlesImage;

    public function staff($request){
        $password = rand(1000000000,9999999999);
        $data = User::create(array_merge($request->all(), ['password' => bcrypt($password), 'temp_password' => $password]));
        $id = $data->id;

        $profile = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'suffix' => $request->suffix,
            'gender' => $request->gender,
            'mobile' => $request->mobile,
            'user_id' => $id
        ];

        if($request->role == 'PSTO Staff'){
            $province = LocationProvince::where('code',$request->province)->first();
            $province->profile()->create($profile);
        }else if($request->role == 'University Coordinator'){
            $school = SchoolCampus::where('id',$request->school)->first();
            $school->profile()->create($profile);
        }else{
            $setting = Setting::with('agency')->first();
            $province = LocationRegion::where('code',$setting->agency->region_code)->first();
            $province->profile()->create($profile);
        }
        ($request->img != '') ? $this->handleImage($id,$request->img,$request->username) : '';
        return new IndexResource($data);
    }

    public static function edit($request){
        $user = User::findOrFail($request->id)->update($request->except('img', 'editable'));
        $profile = UserProfile::where('user_id', $request->id)->first()->update($request->except('email', 'role', 'is_active', 'img', 'editable'));
        $data = User::with('profile')->where('id',$request->id)->first();
        return new IndexResource($data);
    }
}
