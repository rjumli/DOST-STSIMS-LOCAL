<?php

namespace App\Http\Controllers;

use App\Services\Settings\BackupService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public $log;

    public function __construct(BackupService $backup)
    {
        $this->backup = $backup;
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
}
