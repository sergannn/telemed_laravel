<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class WeekDay extends Model
{
    use HasFactory;

    public $table = 'session_week_days';

    public $fillable = [
        'doctor_id',
        'doctor_session_id',
        'day_of_week',
        'start_time',
        'end_time',
        'start_time_type',
        'end_time_type',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'doctor_session_id' => 'integer',
        'day' => 'string',
        'doctor_id' => 'integer',
        'day_of_week' => 'string',
        'start_time' => 'string',
        'end_time' => 'string',
        'start_time_type' => 'string',
        'end_time_type' => 'string',
    ];

    public function getFullStartTimeAttribute()
    {
        return $this->start_time.' '.$this->start_time_type;
    }

    public function getFullEndTimeAttribute()
    {
        return $this->end_time.' '.$this->end_time_type;
    }

    public function doctorSession()
    {
        return $this->belongsTo(DoctorSession::class);
    }
}
