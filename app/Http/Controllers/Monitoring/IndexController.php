<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Monitoring\ViewService;
use App\Services\Monitoring\CountService;
use App\Services\Monitoring\ListService;

class IndexController extends Controller
{
    public function __construct(ViewService $view, CountService $count, ListService $list)
    {
        $this->view = $view;
        $this->count = $count;
        $this->list = $list;
    }

    public function index(Request $request){
        $option = $request->option;
        switch($option){
            case 'lacking_grades':
                return $this->list->lacking_grades($request);
            break;
            case 'unreleased_benefits':
                return $this->list->unreleased_benefits($request);
            break;
            case 'missed_enrollments':
                return $this->list->missed_enrollments($request);
            break;
            case 'terminations':
                return $this->list->terminations($request);
            break;
            default : 
            return inertia('Modules/Monitoring/Index',$this->count->statistics($request));
        }
    }

    public function show($type){
        switch($type){
            case 'schools':
                return inertia('Modules/Monitoring/Schools/Index',$this->view->schools());
            break;
        }
    }
}
