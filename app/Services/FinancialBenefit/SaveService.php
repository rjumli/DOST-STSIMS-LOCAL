<?php

namespace App\Services\FinancialBenefit;

use App\Models\Release;
use App\Models\ScholarEnrollmentBenefit;
use App\Http\Resources\Benefit\ReleaseResource;

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

    public function completed($request){
        $attachments = $this->upload($request);
        $benefit = ScholarEnrollmentBenefit::where('release_id',$request->id)->update(['status_id' => 13]);
        $data = Release::where('id',$request->id)->update(['status_id' => 13, 'attachment' => json_encode($attachments)]);
        $data = Release::where('id',$request->id)->first();
        return new ReleaseResource($data);
    }

    public function upload($request){
        if($request->hasFile('attachment'))
        {   
            $id = $request->batch;
            $year = date('Y');
            $files = $request->file('attachment');   
            foreach ($files as $key=>$file) {
                if($key == 0){
                    $file_name = 'release_'.$id.'_'.$year.'.'.$file->getClientOriginalExtension();
                }else{
                    $file_name = 'release_'.$id.'_'.$year.'-'.$key.'.'.$file->getClientOriginalExtension();
                }
                $file_path = $file->storeAs('benefits', $file_name, 'public');
                
                $attachment[] = [
                    'name' => $file_name,
                    'file' => $file_path,
                    'added_by' => \Auth::user()->id,
                    'created_at' => date('M d, Y g:i a', strtotime(now()))
                ];
            }
            return $attachment;
        }
    }
}
