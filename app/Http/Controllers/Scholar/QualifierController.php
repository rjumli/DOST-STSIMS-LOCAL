<?php

namespace App\Http\Controllers\Scholar;

use App\Services\Qualifier\ApiService;
use App\Services\Qualifier\SaveService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\QualifierRequest;

class QualifierController extends Controller
{
    public $api;
    
    public function __construct(ApiService $api, SaveService $save)
    {
        $this->api = $api;
        $this->save = $save;
    }

    public function index(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
                return $this->api->lists($request);
            break;
            case 'counts':
                return $this->api->counts();
            break;
            default : 
            return inertia('Modules/Scholars/Qualifiers/Index');
        }
    }

    public function store(Request $request){
        $type = $request->type;
        switch($type){
            case 'enroll':
                return $this->save->enroll($request);
            break;
            case 'endorse':
                return $this->save->endorse($request);
            break;
            case 'endorsed':
                return $this->save->endorsed($request);
            break;
            case 'update':
                return $this->save->update($request);
            break;
            case 'edit':
                return $this->save->edit($request);
            break;
        }
    }
}
