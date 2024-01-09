<?php

namespace App\Http\Controllers;

use App\Services\LogService;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public $log;

    public function __construct(LogService $log)
    {
        $this->log = $log;
    }

    public function authentication(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
               return $this->log->authentication($request);
            break;
            default : 
            return inertia('Modules/Logs/Authentications/Index');
        }
    }

    public function activity(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
               return $this->log->activity($request);
            break;
            default : 
            return inertia('Modules/Logs/Activities/Index');
        }
    }
}
