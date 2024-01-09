<?php

namespace App\Services\Sync;

use App\Models\School;
use App\Models\SchoolCourse;
use App\Models\SchoolCampus;
use App\Traits\HandlesCurl;

class SchoolService
{
    use HandlesCurl;

    public function fetchCount(){
        $response = $this->handleCurl('schools','count');
        return json_decode($response);
    }

    public function fetch(){
        set_time_limit(0);
        try {
            $response = $this->handleCurl('schools','data');
            $schools = json_decode($response);
            foreach($schools as $data){
                $school = (array)$data;
                $campuses = array_splice($school,9);
                $school['is_synced'] = 1;
                $q = School::insertOrIgnore($school);
                foreach($data->campuses as $campus)
                {   
                    $lst1 = (array)$campus;
                    $lst = array_pop($lst1);
                    $lst1['is_synced'] = 1;
                    $q = SchoolCampus::insertOrIgnore($lst1);
                    foreach($lst as $course){
                        $course = (array)$course;
                        $course['is_synced'] = 1;
                        $q = SchoolCourse::insertOrIgnore((array)$course);
                    }
                } 
            }
            $response = true;
        }catch (Exception $e){
            $response = 'Caught exception: '.$e->getMessage();
        }
        return $response;
    }
}
