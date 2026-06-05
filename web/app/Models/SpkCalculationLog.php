<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkCalculationLog extends Model
{
    protected $fillable = [
        'department_id',
        'trigger_type',
        'triggered_by',
        'status',
        'candidates_count',
        'notes',
        'duration_ms',
    ];

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'department_id');
    }

    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }
}
