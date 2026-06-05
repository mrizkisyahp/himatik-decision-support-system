<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'candidates';

    protected $fillable = [
        'user_id',
        'candidate_type',
        'nickname',
        'nim',
        'prodi',
        'kelas',
        'phone',
        'address',
        'department_choice_reason',
        'weakness_description',
        'contribution_plan',
        'photo_path',
        'instagram_proof_path',
        'youtube_proof_path',
        'political_statement_path',
        'candidate_signature_path',
        'parent_signature_path',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function firstChoice()
    {
        return $this->hasOne(CandidateDepartmentChoice::class)->where('choice_order', 1);
    }

    public function secondChoice()
    {
        return $this->hasOne(CandidateDepartmentChoice::class)->where('choice_order', 2);
    }

    public function departmentChoices()
    {
        return $this->hasMany(CandidateDepartmentChoice::class)->orderBy('choice_order');
    }

    public function departments()
    {
        return $this->belongsToMany(Departmentsbiro::class, 'candidate_departmentsbiro')
            ->withPivot('choice_order')
            ->withTimestamps()
            ->orderByPivot('choice_order');
    }

    public function getFirstChoiceDepartmentAttribute(): ?Departmentsbiro
    {
        return $this->relationLoaded('departmentChoices')
            ? $this->departmentChoices->firstWhere('choice_order', 1)?->department
            : $this->departmentChoices()->where('choice_order', 1)->first()?->department;
    }

    public function getSecondChoiceDepartmentAttribute(): ?Departmentsbiro
    {
        return $this->relationLoaded('departmentChoices')
            ? $this->departmentChoices->firstWhere('choice_order', 2)?->department
            : $this->departmentChoices()->where('choice_order', 2)->first()?->department;
    }

    public function interviewSchedules()
    {
        return $this->hasOne(CandidateInterviewSchedule::class);
    }

    public function selectedInterviewSchedule()
    {
        return $this->hasOne(CandidateInterviewSchedule::class);
    }

    public function educations()
    {
        return $this->hasMany(CandidateEducation::class);
    }

    public function organizations()
    {
        return $this->hasMany(CandidateOrganization::class);
    }

    public function committees()
    {
        return $this->hasMany(CandidateCommittee::class);
    }

    public function skills()
    {
        return $this->hasMany(CandidateSkill::class);
    }

    public function facilities()
    {
        return $this->hasMany(CandidateFacility::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function announcement()
    {
        return $this->hasOne(Announcement::class);
    }

    public function spkResults()
    {
        return $this->hasMany(SpkResult::class);
    }
}
