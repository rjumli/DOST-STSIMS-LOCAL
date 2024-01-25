<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SchoolSemester;
use App\Services\Settings\BackupService;
use App\Services\Settings\SaveService;
use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;

class SettingController extends Controller
{
    use HandlesTransaction;

    public function __construct(BackupService $backup, SaveService $save)
    {
        $this->backup = $backup;
        $this->save = $save;
    }

    public function backups(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
               return $this->backup->lists($request);
            break;
            default : 
            return inertia('Modules/Settings/Backup/Index');
        }
    }

    public function download($name){
        return $this->backup->download($name);
    }

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            if($request->type == 'year'){
                return $this->save->year($request);
            }else{
                return $this->save->semester($request);
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
