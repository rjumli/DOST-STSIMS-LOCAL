<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Monitoring\ViewService;
use App\Services\Monitoring\CountService;
use App\Services\Monitoring\ListService;
use App\Services\Monitoring\SaveService;
use App\Traits\HandlesTransaction;

class IndexController extends Controller
{
    use HandlesTransaction;

    public function __construct(ViewService $view, CountService $count, ListService $list, SaveService $save)
    {
        $this->view = $view;
        $this->count = $count;
        $this->list = $list;
        $this->save = $save;
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
            case 'batches':
                return [
                    'data' => $this->list->batches($request),
                    'updated' => $this->view->statistics($request,$year = null)
                ];
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
            case 'batches':
                return inertia('Modules/Monitoring/Batch/Index',$this->view->batches($request = null));
            break;
        }
    }

    public function store(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->type){
                case 'releasing':
                    return $this->save->releasing($request);
                break;
                case 'termination':
                    return $this->save->terminate($request);
                break;
                case 'status':
                    return $this->save->status($request);
                break;
                case 'level':
                    return $this->save->level($request);
                break;
            }
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
