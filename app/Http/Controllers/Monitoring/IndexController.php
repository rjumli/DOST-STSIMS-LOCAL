<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Monitoring\ViewService;
use App\Services\Monitoring\CountService;

class IndexController extends Controller
{
    public $view,$count;
    
    public function __construct(ViewService $view, CountService $count)
    {
        $this->view = $view;
        $this->count = $count;
    }

    public function index(Request $request){
        return inertia('Modules/Monitoring/Index',$this->count->statistics($request));
    }

    public function show($type){
        switch($type){
            case 'schools':
                return inertia('Modules/Monitoring/Schools/Index',$this->view->schools());
            break;
        }
    }
}
