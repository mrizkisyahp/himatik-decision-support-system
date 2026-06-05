<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    protected $table = 'evaluation_criteria';

    protected $fillable = [
        'department_id',
        'default_criteria_id',
        'code',
        'name',
        'description',
        'type',
        'aspect',
        'target_score',
        'catatan',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'department_id');
    }

    public function defaultCriteria()
    {
        return $this->belongsTo(DefaultEvaluationCriteria::class, 'default_criteria_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'criteria_id');
    }
}
