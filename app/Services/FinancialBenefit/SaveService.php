<?php

namespace App\Services\FinancialBenefit;

use App\Models\Release;
use App\Models\ScholarEnrollmentBenefit;

class SaveService
{
    public function pending($request){
        $attachment = [];
        $count = Release::whereYear('created_at',now())->count();
        $data = Release::create(
            array_merge($request->all(),[
                'attachment' => json_encode($attachment),
                'added_by' => \Auth::user()->id,
                'batch' => str_pad(($count+1), 5, '0', STR_PAD_LEFT),
                'status_id' => 11
            ])
        );
        foreach($request->lists as $list){
            foreach($list['enrollments'] as $enrollment){
                foreach($enrollment['benefits'] as $benefit){
                    $benefit = ScholarEnrollmentBenefit::where('id',$benefit['id'])->first();
                    $benefit->status_id = 12;
                    $benefit->release_id = $data->id;
                    $benefit->save();
                }
            }
        }
        return $data;
    }
}
