<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkResult extends Model
{
    protected $fillable = [
        'candidate_id',
        'department_id',
        'final_score',
        'personal_core_score',
        'personal_secondary_score',
        'personal_score',
        'organizational_core_score',
        'organizational_secondary_score',
        'organizational_score',
        'rank_position',
        'calculation_details',
        'calculated_by',
        'calculated_at',
    ];

    protected $casts = [
        'calculation_details' => 'array',
        'calculated_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'department_id');
    }
}
