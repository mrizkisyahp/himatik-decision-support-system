<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    protected $table = 'interview_schedules';

    protected $fillable = [
        'department_id',
        'date',
        'start_time',
        'end_time',
        'is_blocked',
    ];

    protected $casts = [
        'date' => 'date',
        'is_blocked' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'department_id');
    }

    public function booking()
    {
        return $this->hasOne(CandidateInterviewSchedule::class);
    }

    public function candidate()
    {
        return $this->hasOneThrough(
            Candidate::class,
            CandidateInterviewSchedule::class,
            'interview_schedule_id',
            'id',
            'id',
            'candidate_id'
        );
    }


}
