<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Monitoring\ViewService;

class IndexController extends Controller
{
    public $view;
    
    public function __construct(ViewService $view)
    {
        $this->view = $view;
    }

    public function index(Request $request){
        return inertia('Modules/Monitoring/Index',$this->view->statistics($request));
    }

    public function show($type){
        switch($type){
            case 'schools':
                return inertia('Modules/Monitoring/Schools/Index',$this->view->schools());
            break;
        }
    }
}
