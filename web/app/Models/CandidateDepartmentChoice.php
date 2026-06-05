<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateDepartmentChoice extends Model
{
    protected $table = 'candidate_departmentsbiro';

    protected $fillable = [
        'candidate_id',
        'departmentsbiro_id',
        'choice_order',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'departmentsbiro_id');
    }
}
