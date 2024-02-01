<?php

namespace App\Services\Insight;

use App\Models\Scholar;
use App\Models\ScholarProfile;
use App\Models\ScholarAddress;
use App\Models\ScholarEducation;
use App\Models\ListProgram;
use App\Models\ListCourse;
use App\Models\ListStatus;
use App\Models\SchoolCampus;
use App\Models\LocationProvince;
use App\Http\Resources\DefaultResource;

class CountService
{
    public function statistics($request){
        return [
            'scholars' => $this->scholars(),
            'countings' => [
                'ongoing' => $this->ongoing(),
                'qualifiers' => $this->qualifiers(),
                'graduates' => $this->graduates()
            ],
            'provinces' => $this->provinces($request),
            'statuses' => $this->statuses($request)
        ];
    }

    public function provinces($request){
        $provinces = ScholarAddress::when($request->region_code, function ($query, $region_code) {
            $query->where('region_code',$region_code);
        })->groupBy('province_code')->pluck('province_code');
        $provinces = LocationProvince::withCount('scholars')->whereIn('code',$provinces)->orderBy('scholars_count','DESC')->get();
        $programs = ListProgram::where('is_sub',1)->get();

        $array = []; $sums = []; $total = [];
        
        foreach($provinces as $key=>$province){
            $code = $province->code;
            $count = [];
            foreach($programs as $key2=>$program){
                $data = Scholar::whereHas('addresses',function ($query) use ($code) {
                    $query->where('province_code',$code)->where('is_permanent',1);
                })
                ->where('program_id',$program->id)->count();
                array_push($count,$data);    
                $sums[$key2][$key] = $data;
            }

            $array[] = [
                'province' => $province->name,
                'code' => $province->code,
                'region_code' => $province->region_code,
                'count' => $count,
                'is_chartered' => $province->is_chartered,
                'total' => array_sum($count)
            ];
        }

        foreach($programs as $key2=>$program){
            $total[] = array_sum($sums[$key2]); 
        }

        $total2 = [
            'province' => 'Total',
            'count' => $total,
            'total' => array_sum($total)
        ];
        
        $all = [
            'provinces' => $array,
            'totals' => $total2,
            'programs' => $programs
        ];

        return $all;
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
            'color' => 'text-success',
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
            'icon' => 'ri-account-circle-fill',
            'color' => 'text-warning',
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
            'color' => 'text-dark',
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

    public function scholars($code = null){
        $array = [];
        $data = Scholar::select('awarded_year AS x',\DB::raw('count(*) AS y'))
        ->when($code, function ($query, $code) {
            $query->whereHas('addresses',function ($query) use ($code) {
                $query->where('province_code',$code);
            });
        })
        ->orderBy('awarded_year')->groupBy('awarded_year')->get();
        $len = count($data);
        
        $arr = [
            'name' => 'Scholars',
            'data' => $data
        ];
        array_push($array,$arr);

        return $arr = [
            'name' => 'Scholars',
            'icon' => 'bxs-user-circle',
            'color' => 'danger',
            'series' => $array,
            'number' => ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => Scholar::when($code, function ($query, $code) {
                $query ->whereHas('addresses',function ($query) use ($code) {
                    $query->where('province_code',$code)->where('is_permanent',1);
                });
            })->count(),
        ];
    }

    public function years($request){
        $provinces = ScholarAddress::where('is_permanent',1)->groupBy('province_code')->pluck('province_code');
        $programs = ListProgram::where('is_sub',1)->get();
        $current_year = $request->year; $years = []; 
        $province = ($request->province) ? $request->province : null;
        $pro = ($request->program) ? $request->program : null;


        $prog = []; 
        foreach($programs as $program){
            $data = []; $year = $current_year - 20;
            for($year; $year <= $current_year; $year++){
                $years[] = $year;
                $data[] = ListProgram::where('id',$program->id)->withCount([
                'scholar', 
                'scholar as scholar_count' => function ($query) use ($year,$province,$pro){
                    $query->where('awarded_year', $year)
                    ->whereHas('addresses',function ($query) use ($province,$pro) {
                        ($province != null) ? $query->where('province_code', $province)->where('is_permanent',1) : '';
                    });
                    ($pro != null) ? $query->where('program_id', $pro) : '';
                }])->pluck('scholar_count')->first();
            }
            $arr[] = [
                'name' => $program->name,
                'data' => $data  
            ];
            
        }

        return $y =[
            'categories' => $years,
            'programs' => $programs,
            'provinces' => LocationProvince::whereIn('code',$provinces)->get(),
            'lists' => $arr
        ];
    }

    public function schools($request){
        $sort = $request->sort;
        $type = $request->scholars;
        $data = SchoolCampus::with('school')
        ->withCount(['scholars' => function ($query) use ($type) {
            $query->when($type, function ($query, $type) {
                if($type == 'ongoing'){
                    $query->whereHas('scholar', function ($query) use ($type) {
                        $query->whereHas('status',function ($query){
                            $query->where('type','ongoing');
                        });
                    });
                }else{
                    $query->whereHas('scholar', function ($query) use ($type) {
                        $query->whereHas('status',function ($query){
                            $query->where('name','Graduated');
                        });
                    });
                }
            });
        }])
        ->orderBy('scholars_count', $sort)->paginate(5)->withQueryString();
        return DefaultResource::collection($data);
    }

    public function courses($request){
        $sort = $request->sort;
        $type = $request->scholars;
        $data = ListCourse::
        withCount(['scholars' => function ($query) use ($type) {
            $query->when($type, function ($query, $type) {
                if($type == 'ongoing'){
                    $query->whereHas('scholar', function ($query) use ($type) {
                        $query->whereHas('status',function ($query){
                            $query->where('type','ongoing');
                        });
                    });
                }else{
                    $query->whereHas('scholar', function ($query) use ($type) {
                        $query->whereHas('status',function ($query){
                            $query->where('name','Graduated');
                        });
                    });
                }
            });
        }])
        ->orderBy('scholars_count', $sort)->paginate(5)->withQueryString();
        return DefaultResource::collection($data);
    }

    public function genders($request){
        $type = $request->scholars;
        $data = ScholarProfile::select(\DB::raw('count(*) as total'))
        ->when($type, function ($query, $type) {
            if($type == 'ongoing'){
                $query->whereHas('scholar', function ($query) use ($type) {
                    $query->whereHas('status',function ($query){
                        $query->where('type','ongoing');
                    });
                });
            }else{
                $query->whereHas('scholar', function ($query) use ($type) {
                    $query->whereHas('status',function ($query){
                        $query->where('name','Graduated');
                    });
                });
            }
        })
        ->whereIn('sex',['F','M'])->groupBy('sex')->get();
        return $data;
    }

    public function statuses($request){
        $statuses = ListStatus::whereIn('type',['Progress','Ongoing'])->get();
        // $provinces = LocationProvince::withCount('scholars')->whereIn('code',$provinces)->orderBy('scholars_count','DESC')->get();
        $types = [['id' => 1,'name' => 'Undergraduate'],['id' => 0, 'name' => 'JLSS']];

        $array = []; $sums = []; $total = [];
        
        foreach($statuses as $key=>$status){
            $id = $status->id;
            $count = [];
            foreach($types as $key2=>$type){
                $data = Scholar::where('status_id',$id)->where('is_undergrad',$type['id'])
                ->count();
                array_push($count,$data);    
                $sums[$key2][$key] = $data;
            }

            $array[] = [
                'status' => $status->name,
                'count' => $count,
                'total' => array_sum($count)
            ];
        }

        foreach($types as $key2=>$type){
            $total[] = array_sum($sums[$key2]); 
        }

        $total2 = [
            'status' => 'Total',
            'count' => $total,
            'total' => array_sum($total)
        ];
        
        $all = [
            'statuses' => $array,
            'totals' => $total2,
            'types' => $types
        ];
        return $all;
    }
}
