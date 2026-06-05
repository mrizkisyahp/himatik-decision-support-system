<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateFacility extends Model
{
    protected $table = 'candidate_facilities';

    protected $fillable = [
        'candidate_id',
        'facility_name',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
