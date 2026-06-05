<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultEvaluationCriteria extends Model
{
    protected $table = 'default_evaluation_criteria';

    protected $fillable = [
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
}
