<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateOrganization extends Model
{
    protected $table = 'candidate_organizations';

    protected $fillable = [
        'candidate_id',
        'organization_name',
        'start_year',
        'end_year',
        'place_or_institution',
        'position',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
