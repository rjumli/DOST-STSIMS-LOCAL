<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\School\ViewService;
use App\Services\School\SaveService;
use App\Services\School\UpdateService;
use App\Traits\HandlesTransaction;
use App\Http\Requests\SchoolProfileRequest;

class SchoolController extends Controller
{
    use HandlesTransaction;
    public $view, $save;

    public function __construct(ViewService $view, SaveService $save, UpdateService $update)
    {
        $this->view = $view;
        $this->save = $save;
        $this->update = $update;
    }

    public function index(Request $request){
        switch($request->type){
            case 'lists':
                return $this->view->fetch($request);
            break;
            case 'semesters':
                return $this->view->semesters($request);
            break;
            case 'counts':
                return $this->view->counts($request->id);
            break;
            default :
            return inertia('Modules/Schools/Index');
        }
    }

    public function store(SchoolProfileRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->type){
                case 'truncate':
                    return $this->truncate($request);
                break;
                case 'semester': 
                    return $this->save->semester($request);
                break;
                case 'prospectus': 
                    return $this->save->prospectus($request);
                break;
                case 'grading':
                    return $this->save->grading($request);
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

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->type){
                case 'prospectus': 
                    return $this->update->prospectus($request);
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

    public function show($id){
        $data = $this->view->view($id);
       
        return inertia('Modules/Schools/Profile/Index',[
            'school' => $data['school'],
            'active' => $data['active']
        ]);
    }
}
