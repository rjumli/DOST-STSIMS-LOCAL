<?php

namespace App\Services\Insight;

use App\Models\Scholar;
use App\Models\ScholarEducation;

class CountService
{
    public function statistics(){
        return [
            'scholars' => $this->scholars(),
            'countings' => [
                'ongoing' => $this->ongoing(),
                'qualifiers' => $this->qualifiers(),
                'graduates' => $this->graduates()
            ]
        ];
    }

    public function ongoing($code = null){
        $array = [];
        $data = Scholar::select('awarded_year AS x',\DB::raw('count(*) AS y'))
        ->when($code, function ($query, $code) {
            $query ->whereHas('addresses',function ($query) use ($code) {
                $query->where('province_code',$code);
            });
        })
        ->whereHas('status',function ($query) {
            $query->where('type','ongoing');
        })
        ->orderBy('awarded_year')->groupBy('awarded_year')->get();
        $len = count($data);
        
        $arr = [
            'name' => 'Ongoing',
            'data' => $data
        ];
        array_push($array,$arr);

        return $arr = [
            'name' => 'Ongoing',
            'icon' => 'bxs-check-circle',
            'color' => 'danger',
            'series' => $array,
            'number' => ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => Scholar::when($code, function ($query, $code) {
                    $query ->whereHas('addresses',function ($query) use ($code) {
                        $query->where('province_code',$code)->where('is_permanent',1);
                    });
                })->whereHas('status',function ($query) {
                    $query->where('type','ongoing');
                })->count(),
        ];
    }

    public function qualifiers($code = null){
        $array = [];
        $data = [];
        $len = count($data);

        $arr = [
            'name' => 'Qualifiers',
            'data' => $data
        ];
        array_push($array,$arr);
        
        return $arr = [
            'name' => 'Qualifiers',
            'icon' => 'bx-notepad',
            'color' => 'primary',
            'series' => $array,
            'number' =>  ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => 0,
        ];
    }

    public function graduates($code = null){
        $array = [];
        $data = ScholarEducation::select('graduated_year AS x',\DB::raw('count(*) AS y'))
        ->when($code, function ($query, $code) {
            $query ->whereHas('scholar',function ($query) use ($code) {
                $query ->whereHas('addresses',function ($query) use ($code) {
                    $query->where('province_code',$code)->where('is_permanent',1);
                });
            });
        })
        ->whereNotNull('graduated_year')
        ->orderBy('graduated_year')->groupBy('graduated_year')->get();
        $len = count($data);

        $arr = [
            'name' => 'Graduated',
            'data' => $data
        ];
        array_push($array,$arr);
        
        return $arr = [
            'name' => 'Graduates',
            'icon' => 'bxs-graduation',
            'color' => 'success',
            'series' => $array,
            'number' =>  ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => ScholarEducation::when($code, function ($query, $code) {
                $query ->whereHas('scholar',function ($query) use ($code) {
                    $query ->whereHas('addresses',function ($query) use ($code) {
                        $query->where('province_code',$code)->where('is_permanent',1);
                    });
                });
            })->whereNotNull('graduated_year')->count(),
        ];
    }

    public function scholars(){

    }
}
