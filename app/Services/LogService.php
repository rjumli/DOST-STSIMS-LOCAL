<?php

namespace App\Services;

use App\Models\Log;
use Spatie\Activitylog\Models\Activity;
use App\Http\Resources\Logs\ActivityResource;
use App\Http\Resources\Logs\AuthenticationResource;

class LogService
{
    public static function authentication($request){
        $data = Log::lists($request->counts);
        return AuthenticationResource::collection($data);
    }

    public static function activity($request){
        $data = Activity::with('causer.profile')->orderBy('created_at','DESC')->paginate($request->counts);
        return ActivityResource::collection($data);
    }
}
