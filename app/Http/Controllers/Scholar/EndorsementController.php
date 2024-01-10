<?php

namespace App\Http\Controllers\Scholar;

use App\Services\Endorsement\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EndorsementController extends Controller
{
    public $api;
    
    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    public function index(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
                return $this->api->lists($request);
            break;
            default : 
            return inertia('Modules/Scholars/Endorsements/Index');
        }
    }
}
