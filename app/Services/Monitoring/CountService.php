<?php

namespace App\Services\Monitoring;

use App\Models\Setting;
use App\Models\Release;
use App\Models\Scholar;
use App\Models\ListStatus;
use App\Models\ListAgency;
use App\Models\SchoolCampus;
use App\Models\SchoolSemester;
use App\Models\ScholarEnrollment;
use App\Http\Resources\Monitoring\LackingResource;

class CountService
{
    public $code;

    public function __construct()
    {
        $setting = Setting::first();
        $this->code = $setting->agency->region_code;
    }

    public function statistics(){
        $total_scholars = Scholar::count();
        return [
            'statistics' => [
                'left' => [
                    'total' => $total_scholars,
                    'ongoing' => $this->ongoing(),
                    'enrolled' => $this->enrolled(),
                    'schools' => $this->schools(),
                    'schools_active' => $this->schools_active(),
                    'monitoring' => [
                        'lacking_grades' => $this->lacking_grades(),
                        'unreleased_benefits' => $this->unreleased_benefits(),
                        'missed_enrollments' => $this->missed_enrollments(),
                        'terminations' => $this->termination()
                    ]
                ],
                'middle' => [
                    'terms' => $this->terms(),
                ],
                'right' => [
                    'statuses' => $this->statuses(),
                    'releasing' => $this->releasing()
                ]
            ]
        ];
    }

    public function termination(){
        $scholars = ScholarEnrollment::select('id','scholar_id','semester_id')
        ->with('semester:id,academic_year,year,semester_id','semester.semester:id,name,others')
        ->with('scholar.status:id,name,color,others')
        ->with([
            'subjects' => function ($query) {
                $query->where('is_failed', 1); // Include only 'lists' where is_failed is 1
            }
        ])
        ->withCount([
        'subjects' => function ($query) {
            $query->where('is_failed',1)->groupBy('enrollment_id');
        }])
        ->whereHas('scholar',function ($query){
            $query ->whereHas('status',function ($query){$query->where('type','ongoing'); });
        })
        ->where('is_checked',0)
        ->having('subjects_count','>', 1)
        ->count();
        return $scholars;
    }

    public function missed_enrollments(){
        $scholars = Scholar::whereHas('status',function ($query){$query->where('type','ongoing');})
            ->whereHas('enrollments', function ($query) { $query->where('is_missed',1); })->count();
        return $scholars;
    }

    public function unreleased_benefits(){
        $date = date('Y-m-d');
        $scholars = Scholar::whereHas('enrollments', function ($query) use ($date){
            $query->whereHas('benefits',function ($query) use ($date){
                $query->whereIn('status_id',[11,12])->where('month','<=',$date);
            });
        })->whereHas('status',function ($query){$query->where('type','ongoing'); })->count();
        return $scholars;
    }

    public function lacking_grades(){
       $scholars = Scholar::whereHas('enrollments', function ($query){
            $query->whereHas('subjects',function ($query){$query->where('grade',NULL);})
            ->whereHas('semester',function ($query){$query->where('is_active',0);});
        })->whereHas('status',function ($query){$query->where('type','ongoing');})->count();
        return $scholars;
    }

    public function ongoing(){
        $data = Scholar::whereHas('status',function ($query){
            $query->where('type','Ongoing');
        })->count();
        return $data;
    }

    public function enrolled(){
        $data = ScholarEnrollment::where('is_enrolled',1)
        ->whereHas('semester',function ($query){
            $query->where('is_active',1);
        })
        ->count();
        return $data;
    }

    public function schools(){
        $agency_id = config('app.agency');
        $region = ListAgency::select('region_code')->where('id',$agency_id)->first();
        $region = $region->region_code;

        $data = SchoolCampus::query()->whereHas('municipality',function ($query) use ($region){
            $query->whereHas('province',function ($query) use ($region){
                $query->whereHas('region',function ($query) use ($region){
                    $query->where('code',$region);
                });
            });
        })->count();
        return $data;
    }

    public function schools_active(){
        $data = SchoolSemester::where('is_active',1)->count();
        return $data;
    }

    public function statuses(){
        $statuses = ListStatus::select('id','name','color','type')->where('type','ongoing')->withCount('scholars')->orderBy('scholars_count', 'desc')->get();
        $substatuses = ListStatus::select('id','name','color','type')->where('is_active',1)->where('type','Status')->withCount('scholars')->get();
    
        return [
            'statuses' => $statuses,
            'substatuses' => $substatuses
        ];
    }

    public function releasing(){
        $data = Release::where('status_id',13)->where('is_checked',0)->count();
        return $data;
    }

    public function terms(){
        return [
            'semester' => SchoolCampus::where('term_id',4)->where('region_code',$this->code)->count(),
            'trimester' => SchoolCampus::where('term_id',5)->where('region_code',$this->code)->count(),
            'quarter' => SchoolCampus::where('term_id',6)->where('region_code',$this->code)->count(),
        ];
    }
}
