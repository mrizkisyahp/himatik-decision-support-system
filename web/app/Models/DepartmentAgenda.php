<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentAgenda extends Model
{
    protected $fillable = [
        'department_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Departmentsbiro::class, 'department_id');
    }
}
