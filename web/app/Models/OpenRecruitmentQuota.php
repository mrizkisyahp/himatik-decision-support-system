<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenRecruitmentQuota extends Model
{
    protected $fillable = [
        'candidate_type',
        'department_id',
        'quota',
    ];

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'department_id');
    }
}
