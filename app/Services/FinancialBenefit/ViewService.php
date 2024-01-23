<?php

namespace App\Services\FinancialBenefit;

use App\Models\Release;
use App\Models\Scholar;
use App\Models\ScholarEnrollment;
use App\Models\ScholarEnrollmentBenefit;
use App\Http\Resources\NameResource;
use App\Http\Resources\Benefit\ReleaseResource;
use App\Http\Resources\Benefit\ListResource;

class ViewService
{
    public function lists($request){
        $month = date_parse($request->month)['month'];
        $year = $request->year;
        
        $data = Release::with('user:id','user.profile:user_id,firstname,lastname,middlename')->orderBy('created_at','DESC')
        ->when($month, function ($query, $month) {
            $query->whereMonth('created_at',$month);
        })
        ->when($year, function ($query, $year) {
            $query->whereYear('created_at',$year);
        })
        ->when($request->keyword, function ($query, $keyword) {
            $query->where('batch','LIKE','%'.$keyword.'%');
        })
        ->paginate($request->counts)
        ->withQueryString();

        return ReleaseResource::collection($data);
    }

    public function generate(){
        
        // $now = Carbon::create(2023, 11, 1, 12, 0, 0);
        // $date = date('Y-m-d H:i:s',strtotime($now));

        $date = now();
        $pending = ScholarEnrollment::whereHas('benefits',function ($query) use ($date){
            $query->where('status_id',11)->where('month','<=',$date);
        })->where('is_enrolled',1)->groupBy('scholar_id')->pluck('scholar_id');
        $scholars = Scholar::with('profile')->whereIn('id',$pending)->get();
        $month = date('Y',strtotime($date)).'-'.date('m',strtotime($date)).'-01';
        $data = [
            'pending' => $pending,
            'scholars' => NameResource::collection($scholars),
            'month' => date('F',strtotime($date)),
            'count' => Release::whereYear('created_at', '=', date("Y"))->whereMonth('created_at', '=', date("m"))->count(),
            'ongoing' => Scholar::whereHas('status', function ($query) {
                $query->where('type','Ongoing');
            })->count(),
            'received' => ScholarEnrollmentBenefit::where('month',$month)->where('benefit_id',1)->where('status_id',13)->count()
        ];
        return $data;
    }

    public function benefits($request){
        $scholars = (!empty(json_decode($request->info))) ? json_decode($request->info) : NULL;
        // dd($scholars);
        $month = now();
        // $now = Carbon::create(2023, 11, 1, 12, 0, 0);
        // $month = date('Y-m-d',strtotime($now));
        // dd($date);
        $data = Scholar::select('id','program_id')
        ->with('program:id,name')
        ->with('profile:account_no,scholar_id,firstname,lastname,middlename')
        ->withWhereHas('enrollments', function ($query) use ($month) {
            $query->select('id','scholar_id','semester_id')
            ->withWhereHas('semester', function ($query) {
                $query->select('id','academic_year','semester_id')
                ->withWhereHas('semester', function ($query) {
                    $query->select('id','name');
                });
            })
            ->withWhereHas('benefits', function ($query) use ($month) {
                $query->select('id','enrollment_id','amount','month','benefit_id')->where('status_id',11)->where('month','<=',$month)
                ->withWhereHas('benefit', function ($query) {
                    $query->select('id','name','type','short','regular_amount','summer_amount');
                });
            });
        })
        ->whereIn('id',$scholars)
        ->get();
        return ListResource::collection($data);
    }
}
