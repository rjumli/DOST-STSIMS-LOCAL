<?php

namespace App\Services\Scholar;

use App\Models\Scholar;
use App\Models\ListProgram;
use App\Models\ListAgency;
use App\Models\ListDropdown;
use App\Exports\ReportExport;
use App\Http\Resources\Scholar\ReportResource;

class ReportService
{
    public function print($request){
        ini_set('memory_limit', '-1');
        $info = (!empty(json_decode($request->info))) ? json_decode($request->info) : NULL;

        $agency_id = config('app.agency');
        $agency = ListAgency::with('region')->where('id',$agency_id)->first();
        $type = $info->type;

        if($type != 'awardees'){
            $data = Scholar::select('id','spas_id','awarded_year','status_id','program_id','subprogram_id')
            ->with('profile:id,scholar_id,firstname,lastname,middlename')
            ->with('status:id,name')
            ->with('program:id,name')
            ->with('subprogram:id,name')
            ->with('addresses.municipality')
            ->withWhereHas('education', function ($query) use ($type){
                $query->select('id','scholar_id','school_id','level_id','course_id','award_id')
                ->withWhereHas('school', function ($query){
                    $query->select('id','school_id','campus')
                    ->withWhereHas('school', function ($query){
                        $query->select('id','name','shortcut');
                    });
                })->withWhereHas('course', function ($query) use ($type){
                    $query->select('id','name');
                })->with('award:id,name');
                if($type == 'scholars'){
                    $query->withWhereHas('level', function ($query){
                        $query->select('id','name');
                    });
                }
            })
            // ->where('is_completed',1)
            ->where(function($query) use ($info) {  
                ($info->program == '') ? '' : $query->where('program_id',$info->program);
                ($info->status == '') ? '' : $query->where('status_id',$info->status);
                ($info->from != '' && $info->to != '') ? $query->whereBetween('awarded_year',[$info->from,$info->to]) : '';
            })
            ->when($type, function ($query, $type) {
                if($type == 'graduates'){
                    $query->where('status_id', 2);
                }
            })
            ->orderBy('awarded_year','DESC')->get();
            // dd($data);
            $scholars = ReportResource::collection($data);
        //   dd($scholars);
            $array = [
                'scholars' => $scholars,
                'agency' => $agency
            ];
        }

        switch($type){
            case 'scholars':
                return $this->scholarlists($array);
            break;
            case 'graduates':
                return $this->graduates($array);
            break;
            case 'awardees':
                return $this->awardees($info,$agency);
            break;
        }
    }

    public function scholarlists($array){
        // $pdf = \PDF::loadView('reports.scholars',$array)->setPaper('a4', 'landscape');
        // return $pdf->download('List of Scholars.pdf');
        // dd($array['scholars']);
        foreach($array['scholars'] as $arr){
            $test[] = [
                $arr['id'],
                $arr['profile']['lastname'].', '.$arr['profile']['middlename'].' '.$arr['profile']['middlename'],
                $arr['education']['school']['school']['shortcut'],
                $arr['education']['course']['name'],
                $arr['education']['level']['name'],
                $arr['status']['name'],
                $arr['addresses'][0]['municipality']['name'],
                $arr['subprogram']['name']
            ];
        }

        return \Excel::download(new ReportExport($test), 'List_of_Scholars.xlsx');
    }

    public function graduates($array){
        $pdf = \PDF::loadView('reports.graduates',$array)->setPaper('a4', 'landscape');
        return $pdf->download('List of Graduate Scholars.pdf');
    }

    public function awardees($info,$agency){
        $year = ($info->year) ? $info->year : date('Y')-1;
        $programs = ListProgram::where('is_sub',1)->get();
        $awards = ListDropdown::where('classification','Award')->get();
        $array['awards'] = $awards; $total_no = 0; $total_awardee = 0; 
        $totals = [];
        
        foreach($programs as $index=>$program){
            $data = Scholar::whereHas('status', function ($query) {
                $query->where('name','Graduated');
            })->whereHas('education',function ($query) use ($year) {
                $query->where('graduated_year',$year);
            })->where('program_id',$program->id)->count();
            $list = [];

            foreach($awards as $index2=>$award){
                $count = Scholar::where('program_id',$program->id)
                ->whereHas('education',function ($query) use ($award,$year) {
                    $query->where('award_id',$award->id)->where('graduated_year',$year);
                })->count();
                // dd($count);
                array_push($list,$count);
            }

            $array2[] = [
                'name' => $program->name,
                'count' => $data,
                'list' => $list,
                'total' => array_sum($list)
            ];
            $total_no += $data;
            $total_awardee += array_sum($list);
        }

        foreach($awards as $index2=>$award){ 
            $tot = 0;
            foreach($programs as $index=>$program){
                $tot += $array2[$index]['list'][$index2];
            }
            array_push($totals,$tot);
        }

        $array2[] = [
            'name' => 'Total',
            'count' => $total_no,
            'list' => $totals,
            'total' => $total_awardee
        ];
        // dd($array2);
        $array['scholars'] = $array2;
        $array['agency'] = $agency;

        $pdf = \PDF::loadView('reports.awardees',$array)->setPaper('a4', 'landscape');
        return $pdf->download('List of Graduate Scholars with Awards.pdf');
    }
}
