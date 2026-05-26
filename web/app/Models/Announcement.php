<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';

    protected $fillable = [
        'candidate_id', 'assigned_department_id', 'status', 'is_published', 'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'assigned_department_id');
    }
}
