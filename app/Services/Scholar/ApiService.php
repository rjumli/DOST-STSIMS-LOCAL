<?php

namespace App\Services\Scholar;

use App\Models\Scholar;
use App\Models\ScholarProfile;
use App\Models\ScholarAddress;
use App\Models\ScholarEducation;
use App\Traits\HandlesCurl;

class ApiService
{
    use HandlesCurl;
    public $link, $api;

    public function __construct()
    {
        $this->link = config('app.api_link');
        $this->api = config('app.api_key');
    }

    public function option($request){
        $subtype = $request->subtype;
        switch($subtype){
            case 'download':
                return $this->download();
            break;
            case 'sync':
                return $this->sync();
            break;
        }
    }

    public function download(){
        $response = $this->handleCurl('scholars','kradworkz');
        $lists = json_decode($response);
        try{
            $result = \DB::transaction(function () use ($lists){
                $users = array();
                $success = array();
                $failed = array();
                $duplicate = array();
                
                foreach($lists as $data){
                    $arr = (array)$data;
                    $c = Scholar::count();
                    $stsims = 'STSIMS-'.$arr['awarded_year']."-".str_pad(($c+1), 5, '0', STR_PAD_LEFT);  
                    $sub = array_splice($arr,11);
                    $arr['stsims_id'] = $stsims;
                    $arr['is_synced'] = 1;
                    $spas_id = $arr['spas_id'];
                    unset($arr['id']);
                    $count = Scholar::where('spas_id',$spas_id)->count();
                    if($count == 0){
                        $address = $sub['address'];
                        unset($address->id);
                        $education = $sub['education'];
                        unset($education->id);
                        $profile = $sub['profile'];
                        unset($profile->id);
                

                        // dd($address,$education,$profile);
                        \DB::beginTransaction();
                        $q = Scholar::insertOrIgnore($arr);
                        Scholar::where('spas_id',$spas_id)->where('subprogram_id',26)->update(['is_undergrad' => 0]);
                        $isko =  Scholar::select('id')->where('spas_id',$spas_id)->first();

                        if($q){
                            $address->scholar_id = $isko->id;
                            $education->scholar_id = $isko->id;
                            $profile->scholar_id = $isko->id;
                            $address = (array)$address;
                            $address['is_synced'] = 1;
                            $a = ScholarAddress::insertOrIgnore($address);
                            if($a){
                                $profile = (array)$profile;
                                $profile['is_synced'] = 1;
                                $b = ScholarProfile::insertOrIgnore($profile);
                                if($b){
                                    $education = (array)$education;
                                    $education['is_synced'] = 1;
                                    $c = ScholarEducation::insertOrIgnore($education);
                                    if($c){
                                        array_push($success,$spas_id);
                                        \DB::commit();
                                    }else{
                                        array_push($failed,$spas_id);
                                        \DB::rollback();
                                    }
                                }else{
                                    array_push($failed,$spas_id);
                                    \DB::rollback();
                                }
                            }else{
                                array_push($failed,$spas_id);
                                \DB::rollback();
                            }
                        }else{
                            array_push($failed,$spas_id);
                            \DB::rollback();
                        }
                    }else{
                        array_push($duplicate,$spas_id);
                    }
                }

                $result = [
                    'success' => $success,
                    'failed' => $failed,
                    'duplicate' => $duplicate,
                ];
                return $result;
            });
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return $result;
    }

    public function sync(){
        
        $scholars = Scholar::where('is_synced',0)->get();
        $profiles = ScholarProfile::with('scholar:id,spas_id')->where('is_synced',0)->get();
        $addresses = ScholarAddress::with('scholar:id,spas_id')->where('is_synced',0)->get();
        $educations = ScholarEducation::with('scholar:id,spas_id')->where('is_synced',0)->get();

        $postData = array(
            'scholars' => $scholars,
            'addresses' => $addresses,
            'educations' => $educations,
            'profiles' => $profiles,
            'type' => 'sync'
        );

        try{
            $response = $this->handleCurl('scholars',null);
            $datas = json_decode($response);

            $synced = $datas->success;
            foreach($synced as $s){
                $spas_id = $s->spas_id;
                switch($s->type){
                    case 'address':
                        $check = ScholarAddress::whereHas('scholar',function ($query) use ($spas_id) {
                            $query->where('spas_id',$spas_id);
                        })->update(['is_synced' => 1]);
                    break;
                    case 'profile':
                        ScholarProfile::whereHas('scholar',function ($query) use ($spas_id) {
                            $query->where('spas_id',$spas_id);
                        })->update(['is_synced' => 1]);
                    break;
                    case 'education':
                        ScholarEducation::whereHas('scholar',function ($query) use ($spas_id) {
                            $query->where('spas_id',$spas_id);
                        })->update(['is_synced' => 1]);
                    break;
                    case 'scholar':
                        Scholar::where('spas_id',$spas_id)->update(['is_synced' => 1]);
                    break;
                }
            }

            return back()->with([
                'message' => 'Scholars synced successfully. Thanks',
                'data' =>  count($synced),
                'type' => 'bxs-check-circle'
            ]); 

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
