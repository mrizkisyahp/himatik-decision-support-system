<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateEducation extends Model
{
    protected $table = 'candidate_educations';

    protected $fillable = [
        'candidate_id',
        'education_type',
        'school_name',
        'start_year',
        'end_year',
        'city',
        'major',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
