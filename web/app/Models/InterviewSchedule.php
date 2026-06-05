<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    protected $table = 'interview_schedules';

    protected $fillable = [
        'department_id',
        'session_name',
        'scheduled_at',
        'location',
        'is_active',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_active' => 'boolean',
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

    public function interviewers()
    {
        return $this->belongsToMany(User::class, 'interviewer_schedule');
    }

}
