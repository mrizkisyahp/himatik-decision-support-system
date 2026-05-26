<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'candidates';

    protected $fillable = [
        'user_id',
        'nim',
        'prodi',
        'kelas',
        'phone',
        'first_choice_id',
        'second_choice_id',
        'recruitment_form_path',
        'photo_path',
        'statement_letter_path',
        'social_media_proof_path',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function firstChoice()
    {
        return $this->belongsTo(Departmentsbiro::class, 'first_choice_id');
    }

    public function secondChoice()
    {
        return $this->belongsTo(Departmentsbiro::class, 'second_choice_id');
    }

    public function interviewSchedules()
    {
        return $this->hasOne(InterviewSchedule::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function announcement()
    {
        return $this->hasOne(Announcement::class);
    }
}
