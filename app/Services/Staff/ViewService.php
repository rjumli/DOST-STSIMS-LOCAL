<?php

namespace App\Services\Staff;

use App\Models\Log;
use App\Models\User;
use App\Http\Resources\Staff\IndexResource;

class ViewService
{   
    public static function lists($request){
        $data = IndexResource::collection(
            User::query()
            ->with('profile')
            ->when($request->keyword, function ($query, $keyword) {
                $query->whereHas('profile',function ($query) use ($keyword) {
                    $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', "%{$keyword}%")
                    ->orWhere(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', "%{$keyword}%");
                })->orWhere(function ($query) use ($keyword) {
                    $query->where('username', 'LIKE', "%{$keyword}%")->whereNotIn('role',['Scholar','Administrator']);
                });
            })
            ->when($request->role, function ($query, $role) {
                $query->where('role',$role);
            })
            ->whereNotIn('role',['Scholar','Administrator'])
            ->paginate($request->counts)
            ->loadMorph('profile.profileable', [ 
                SchoolCampus::class => ['school'],
            ])
        );

        return $data;
    }

    public function statistics(){
        $array = [
            Log::where('login_successful',1)->count(),
            Log::where('login_successful',1)->where('ip_address','!=','::1')->count(),
            Log::where('login_successful',0)->count(),
            $this->attempts()
        ];
        return $array;
    }

    public function attempts(){
        $usersWithFailedLogins = Log::select('authenticatable_id', \DB::raw('count(*) as login_attempts'))
        ->where('login_successful', 0)
        ->groupBy('authenticatable_id')
        ->having('login_attempts', '>=', 5)
        ->whereDate('login_at',now())
        ->get();
        
    //   dd($usersWithFailedLogins);
    return $usersWithFailedLogins;
    }

}
