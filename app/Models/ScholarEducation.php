<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Spatie\Activitylog\Traits\LogsActivity;
use \Spatie\Activitylog\LogOptions;

class ScholarEducation extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logFillable()
        ->setDescriptionForEvent(fn(string $eventName) => "This scholar education has been {$eventName}")
        ->useLogName('Scholar Education Table')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'information',
        'course_id',
        'subcourse_id',
        'school_id',
        'level_id',
        'award_id',
        'graduated_year',
        'is_completed',
        'is_enrolled',
        'is_shiftee',
        'is_transferee',
        'scholar_id'
    ];

    public function scholar()
    {
        return $this->belongsTo('App\Models\Scholar', 'scholar_id', 'id');
    }
    
    public function school()
    {
        return $this->belongsTo('App\Models\SchoolCampus', 'school_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\ListCourse', 'course_id', 'id');
    }

    public function subcourse()
    {
        return $this->belongsTo('App\Models\SchoolCourse', 'subcourse_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'level_id', 'id');
    }

    public function award()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'award_id', 'id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
}
