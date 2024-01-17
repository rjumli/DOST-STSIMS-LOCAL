<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Spatie\Activitylog\Traits\LogsActivity;
use \Spatie\Activitylog\LogOptions;

class ScholarProfile extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logFillable()
        ->setDescriptionForEvent(fn(string $eventName) => "This scholar profile has been {$eventName}")
        ->useLogName('Scholar Profile Table')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'email',
        'account_no',
        'contact_no',
        'firstname', 
        'lastname', 
        'middlename',
        'suffix',
        'sex',
        'birthday',
        'information',
        'is_completed',
        'scholar_id'
    ];

    public function scholar()
    {
        return $this->belongsTo('App\Models\Scholar', 'scholar_id', 'id');
    }

    public function getFirstnameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function getLastnameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function getMiddlenameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    // public function getSexAttribute($value)
    // {
    //     switch ($value)
	// 	{
	// 		case 'M': $sex='Male';break;
	// 		case 'F': $sex='Female';break;
	// 		default:$sex=NULL;break;
	// 	}
    //     return $sex;
    // }
}
