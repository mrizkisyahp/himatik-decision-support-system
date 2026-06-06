<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenRecruitment extends Model
{
    protected $fillable = [
        'candidate_type',
        'starts_at',
        'ends_at',
        'status',
        'interview_location',
        'interview_requirements',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function extensions()
    {
        return $this->hasMany(OpenRecruitmentExtension::class);
    }

    public function quotaLogs()
    {
        return $this->hasMany(OpenRecruitmentQuotaLog::class, 'candidate_type', 'candidate_type');
    }

    public function isCurrentlyOpen(): bool
    {
        $now = now();

        return $this->status === 'open'
            && $this->starts_at
            && $this->ends_at
            && $now->betweenIncluded($this->starts_at, $this->ends_at);
    }
}
