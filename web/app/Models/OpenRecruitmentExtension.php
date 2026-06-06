<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenRecruitmentExtension extends Model
{
    protected $fillable = [
        'open_recruitment_id',
        'old_starts_at',
        'old_ends_at',
        'new_starts_at',
        'new_ends_at',
        'reason',
        'extended_by',
    ];

    protected $casts = [
        'old_starts_at' => 'datetime',
        'old_ends_at' => 'datetime',
        'new_starts_at' => 'datetime',
        'new_ends_at' => 'datetime',
    ];

    public function openRecruitment()
    {
        return $this->belongsTo(OpenRecruitment::class);
    }

    public function extender()
    {
        return $this->belongsTo(User::class, 'extended_by');
    }
}
