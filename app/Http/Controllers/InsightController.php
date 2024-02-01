<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Insight\CountService;

class InsightController extends Controller
{
    public function __construct(CountService $count)
    {
        $this->count = $count;
    }

    public function index(Request $request){
        $type = $request->type;
        switch($type){
            case 'years':
                return $this->count->years($request);
            break;
            case 'schools':
                return $this->count->schools($request);
            break;
            case 'courses':
                return $this->count->courses($request);
            break;
            case 'genders':
                return $this->count->genders($request);
            break;
            case 'statuses':
                return $this->count->statuses($request);
            break;
            default : 
            return inertia('Modules/Insights/Index',[
                'statistics' => $this->count->statistics($request)
            ]);
        }
    }
}
