<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenRecruitmentQuotaLog extends Model
{
    protected $fillable = [
        'candidate_type',
        'department_id',
        'old_quota',
        'new_quota',
        'changed_by',
    ];

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'department_id');
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
