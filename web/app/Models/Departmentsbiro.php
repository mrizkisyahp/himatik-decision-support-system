<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departmentsbiro extends Model
{
    protected $table = 'departmentsbiro';

    protected $fillable = ['name', 'description', 'core_factor_weight', 'secondary_factor_weight'];

    public function evaluationCriteria()
    {
        return $this->hasMany(EvaluationCriteria::class, 'department_id');
    }

    public function firstChoiceCandidates()
    {
        return $this->hasMany(Candidate::class, 'first_choice_id');
    }

    public function secondChoiceCandidates()
    {
        return $this->hasMany(Candidate::class, 'second_choice_id');
    }
}
