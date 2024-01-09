<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Staff\SaveService;
use App\Services\Staff\ViewService;
use App\Http\Requests\StaffRequest;
use App\Traits\HandlesTransaction;

class ListController extends Controller
{   
    use HandlesTransaction;
    public $save, $view;

    public function __construct(SaveService $save, ViewService $view)
    {
        $this->save = $save;
        $this->view = $view;
    }

    public function index(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
               return $this->view->lists($request);
            // return [];
            break;
            default : 
            return inertia('Modules/Staffs/Lists/Index',
                ['statistics' => $this->view->statistics()]
            );
        }
    }

    public function store(StaffRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->staff($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->edit($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

}
