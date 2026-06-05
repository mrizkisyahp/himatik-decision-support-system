<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateInterviewSchedule extends Model
{
    protected $fillable = [
        'candidate_id',
        'interview_schedule_id',
        'department_id',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function schedule()
    {
        return $this->belongsTo(InterviewSchedule::class, 'interview_schedule_id');
    }

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'department_id');
    }
}
