<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    protected $table = 'evaluation_criteria';

    protected $fillable = [
        'department_id', 'name', 'type', 'target_score', 'description'
    ];

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'department_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'criteria_id');
    }
}
