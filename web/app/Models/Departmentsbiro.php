<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departmentsbiro extends Model
{
    protected $table = 'departmentsbiro';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'contact_person',
        'personal_aspect_weight',
        'organizational_aspect_weight',
        'core_factor_weight',
        'secondary_factor_weight',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'personal_aspect_weight' => 'decimal:2',
        'organizational_aspect_weight' => 'decimal:2',
        'core_factor_weight' => 'decimal:2',
        'secondary_factor_weight' => 'decimal:2',
    ];

    public function evaluationCriteria()
    {
        return $this->hasMany(EvaluationCriteria::class, 'department_id');
    }

    public function firstChoiceCandidates()
    {
        return $this->belongsToMany(Candidate::class, 'candidate_departmentsbiro')
            ->wherePivot('choice_order', 1)
            ->withPivot('choice_order')
            ->withTimestamps();
    }

    public function secondChoiceCandidates()
    {
        return $this->belongsToMany(Candidate::class, 'candidate_departmentsbiro')
            ->wherePivot('choice_order', 2)
            ->withPivot('choice_order')
            ->withTimestamps();
    }

    public function candidateChoices()
    {
        return $this->hasMany(CandidateDepartmentChoice::class, 'departmentsbiro_id');
    }

    public function schedules()
    {
        return $this->hasMany(InterviewSchedule::class, 'department_id');
    }

    public function spkResults()
    {
        return $this->hasMany(SpkResult::class, 'department_id');
    }

    public function workPrograms()
    {
        return $this->hasMany(DepartmentWorkProgram::class, 'department_id');
    }

    public function agendas()
    {
        return $this->hasMany(DepartmentAgenda::class, 'department_id');
    }

    public function openRecruitmentQuotas()
    {
        return $this->hasMany(OpenRecruitmentQuota::class, 'department_id');
    }
}
