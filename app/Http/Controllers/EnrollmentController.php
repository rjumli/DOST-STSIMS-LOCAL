<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;
use App\Services\Enrollment\ViewService;
use App\Services\Enrollment\SaveService;

class EnrollmentController extends Controller
{
    use HandlesTransaction;
    public $view,$save;
    
    public function __construct(ViewService $view, SaveService $save)
    {
        $this->view = $view;
        $this->save = $save;
    }

    public function index(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
                return $this->api->lists($request);
            break;
            case 'search':
                return $this->view->search($request);
            break;
            case 'activeprospectus':
                return $this->view->activeprospectus($request);
            break;
            default : 
            return inertia('Modules/Enrollments/Index');
        }
    }

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->type){
                case 'enrollment': 
                    return $this->view->enrollment($request);
                break;
                case 'switch': 
                    return $this->save->switch($request);
                break;
            }
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
            'type' => $request->type
        ]);
    }
}
