<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateCommittee extends Model
{
    protected $table = 'candidate_committees';

    protected $fillable = [
        'candidate_id',
        'committee_name',
        'start_year',
        'end_year',
        'organizer',
        'position',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
