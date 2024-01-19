<?php

namespace App\Http\Controllers\Scholar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ScholarRequest;
use App\Services\Scholar\ApiService;
use App\Services\Scholar\ViewService;
use App\Services\Scholar\SaveService;
use App\Services\Scholar\ReportService;
use App\Services\Scholar\TruncateService;
use App\Traits\HandlesTransaction;
use App\Exports\EntryExport;
use Maatwebsite\Excel\Facades\Excel;

class ListController extends Controller
{
    use HandlesTransaction;
    public $api, $view, $truncate, $report;
    
    public function __construct(ApiService $api, ViewService $view, SaveService $save, TruncateService $truncate, ReportService $report)
    {
        $this->api = $api;
        $this->view = $view;
        $this->save = $save;
        $this->truncate = $truncate;
        $this->report = $report;
    }

    public function index(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
                return $this->view->lists($request);
            break;
            case 'enrollments':
                return $this->view->enrollments($request);
            break;
            case 'search':
                return $this->search($request);
            break;
            case 'print':
                return $this->report->print($request);
            break;
            case 'export':
                return $this->export();
            break;
            default : 
            return inertia('Modules/Scholars/Lists/Index',$this->view->statistics());
        }
    }

    public function store(ScholarRequest $request){
        $type = $request->type;
        switch($type){
            case 'truncate':
               return $this->truncate->truncate($request);
            break;
            case 'api':
                return $this->api->option($request);
            break;
            case 'create':
                $result = $this->handleTransaction(function () use ($request) {
                    return $this->save->create($request);
                });
                return back()->with([
                    'data' => $result['data'],
                    'message' => $result['message'],
                    'info' => $result['info'],
                    'status' => $result['status'],
                ]);
            break;
        }
    }

    public function export()
    {
        return Excel::download(new EntryExport, 'users.xls');
    }
}
