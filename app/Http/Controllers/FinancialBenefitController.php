<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;
use App\Http\Requests\ReleaseRequest;
use App\Services\FinancialBenefit\ViewService;
use App\Services\FinancialBenefit\SaveService;

class FinancialBenefitController extends Controller
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
                return $this->view->lists($request);
            break;
            case 'benefits':
                return $this->view->benefits($request);
            break;
            default : 
            return inertia('Modules/FinancialBenefits/Index',[
                'latest' => $this->view->generate()
            ]);
        }
    }

    public function store(ReleaseRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            $type = $request->type;
            switch($type){
                case 'completed':
                    return $this->completed($request);
                break;
                case 'pending':
                    return $this->save->pending($request);
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
