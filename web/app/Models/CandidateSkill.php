<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateSkill extends Model
{
    protected $table = 'candidate_skills';

    protected $fillable = [
        'candidate_id',
        'skill_type',
        'skill_name',
        'proficiency',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
