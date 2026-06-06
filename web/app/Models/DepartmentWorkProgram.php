<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentWorkProgram extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'description',
        'period',
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
}
