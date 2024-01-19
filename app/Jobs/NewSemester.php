<?php

namespace App\Jobs;

use App\Models\Scholar;
use App\Models\ScholarEnrollment;
use App\Models\SchoolSemester;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewSemester implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id,$school_id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function handle(): void
    {   
        $data = SchoolSemester::with('semester')->where('id',$this->id)->first();
        $semester_id = $data->id;
        $school_id = $data->school_id;
        $semester_name = $data->semester->name;

        if($this->id != null){
            $scholars = Scholar::select('id')->whereHas('status',function ($query){
                $query->where('type','ongoing');
            })->withWhereHas('education',function ($query) use ($school_id,$semester_name){
                $query->select('id','scholar_id','level_id')->where('school_id',$school_id)
                ->withWhereHas('level',function ($query) use ($semester_name){
                    $query->select('id','name','others');
                });
            })->get();

            $enrollmentsData = $scholars->map(function ($scholar) use ($semester_id,$semester_name){
                $fill = [
                    'scholar_id' => $scholar->id,
                    'semester_id' => $semester_id,
                    'level_id' => $scholar->education->level_id,
                    'attachment' => json_encode([
                        'grades' => [],
                        'enrollments' => []
                    ]),
                    'added_by' => \Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if($semester_name == 'Summer'){
                    if($scholar->education->level->others == 'Third Year'){
                        return $fill;
                    }else{
                        return [];
                    }
                }else{
                    return $fill;
                }
            });
            $enrollments = ScholarEnrollment::insert($enrollmentsData->all());
        }
    }
}
