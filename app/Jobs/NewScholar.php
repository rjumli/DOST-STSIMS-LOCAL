<?php

namespace App\Jobs;

use App\Models\Scholar;
use App\Models\SchoolSemester;
use App\Models\ScholarEnrollment;
use App\Models\ScholarEducation;
use App\Models\ScholarEnrollmentBenefit;
use App\Models\ListPrivilege;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewScholar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function handle(): void
    {
        if($this->id != null){
            $scholar = Scholar::with('education')->where('id',$this->id)->first();
            if($scholar){
                $awarded_year = $scholar->awarded_year;
                $school_id = $scholar->education->school_id;
                $level_id = $scholar->education->level_id;

                $semesters = SchoolSemester::where('school_id',$school_id)->where('year','>=',$awarded_year)->get();
                if(count($semesters) > 0){
                    foreach($semesters as $semester){

                        $information = [
                            'scholar_id' => $this->id,
                            'semester_id' => $semester->id,
                            'level_id' => $level_id ,
                            'attachment' => json_encode([
                                'grades' => [],
                                'enrollments' => []
                            ]),
                            'added_by' => \Auth::user()->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        
                        $data = ScholarEnrollment::create($information);

                        if($data){
                            $month = $semester->start_at;
                            $list_benefits = ListPrivilege::where('is_reimburseable',0)->get();
                            $type = ScholarEducation::with('school.term')->where('scholar_id',$this->id)->first();
                            $term_name = $type->school->term->name;
                            $others = $data->semester->semester->others;

                            switch($term_name){
                                case 'Semester': 
                                    $div = 2;
                                break;
                                case 'Trimester':
                                    $div = 3;
                                break;
                                case 'Quarter Term':
                                    $div = 4;
                                break;
                            }

                            foreach($list_benefits as $benefit){
                                $attachment = [];
                                $amount = ($others != 'summer') ? $benefit['regular_amount'] : $benefit['summer_amount'];
                                if($others != 'summer'){
                                    $total = $amount / (($benefit['type'] == 'Term') ? $div : 1);
                                }else{
                                    $total = $amount;
                                }
                                $wew = [
                                    'benefit_id' => $benefit['id'],
                                    'enrollment_id' => $data->id,
                                    'amount' => $total,
                                    'release_type' => 'Full',
                                    'month' => $month,  
                                    'status_id' => 11,
                                    'attachment' => json_encode($attachment)
                                ];
                    
                    
                                if($benefit['id'] == 1){
                                    $number = ($others != 'summer') ? 5 : 2; 
                                    for($x = 0; $x < $number; $x++){
                                        $list = ScholarEnrollmentBenefit::create($wew);
                                        $wew['month'] = date('Y-m-d', strtotime($wew['month']. ' + 1 months'));
                                    }
                                }else if($benefit['name'] == 'Clothing Allowance'){
                                    $s_id = $this->id;
                                    $count = ScholarEnrollmentBenefit::whereHas('enrollment',function ($query) use ($s_id) {
                                        $query->whereHas('scholar',function ($query) use ($s_id) {
                                            $query->where('id',$s_id);
                                        });
                                    })->where('benefit_id',$benefit['id'])->count();
                                    ($count == 0) ? $list = ScholarEnrollmentBenefit::create($wew) : '';
                                }else{
                                    $list = ScholarEnrollmentBenefit::create($wew);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
