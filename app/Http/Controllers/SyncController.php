<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Sync\ListService;
use App\Services\Sync\SchoolService;
use App\Services\Sync\LocationService;

class SyncController extends Controller
{
    public $location;

    public function __construct(ListService $list, SchoolService $school, LocationService $location)
    {
        $this->list = $list;
        $this->school = $school;
        $this->location = $location;
    }

    public function checkApi(){
        return $this->location->checkConnection();
    }

    public function fetchCount(){
        $array = [
            'lists' => $this->list->fetchCount(),
            'schools' => $this->school->fetchCount(),
            'locations' => $this->location->fetchCount(),
        ];
        return $array;
    }

    public function locations(){
        return $this->location->fetch();
    }

    public function schools(){
        return $this->school->fetch();
    }

    public function lists(){
        return $this->list->fetch();
    }
}
