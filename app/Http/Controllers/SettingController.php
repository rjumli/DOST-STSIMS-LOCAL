<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SchoolSemester;
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

    public function update(Request $request){
        $data = \DB::transaction(function () use ($request){
            $data = Setting::first();

            if($request->type == 'year'){
                $data->year = $request->year;
                $data->academic_year = $request->academic_year;
                $data->semester_id = NULL;
                $data->trimester_id = NULL;
                $data->quarter_id = NULL;
                SchoolSemester::query()->update(['is_active' => 0]);
            }else if($request->type == 'Semester'){
                $data->semester_id = $request->semester;
            }else if($request->type == 'Trimester'){
                $data->trimester_id = $request->semester;
            }else if($request->type == 'Quarter Term'){
                $data->quarter_id = $request->semester;
            }
            $data->save();
           
            return $data;
        });
        
        return back()->with([
            'message' => 'System configuration updated successfully',
            'data' => $data,
            'info' => '',
            'status' => true
        ]);
    }
}
