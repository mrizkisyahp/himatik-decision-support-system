<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    protected $table = 'interview_schedules';

    protected $fillable = [
        'session_name',
        'scheduled_at',
        'location',
        'candidate_id'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function interviewers()
    {
        return $this->belongsToMany(User::class, 'interviewer_schedule');
    }

}
