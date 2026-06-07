<?php

namespace App\Support;

class CandidateProfileRules
{
    public static function rules(?int $userId = null): array
    {
        $nimRule = $userId ? 'unique:users,nim,' . $userId : 'unique:users,nim';
        return [
            'candidate_type' => 'required|in:staff,bph',
            'nickname' => 'required|string|max:255',
            'nim' => ['required', 'digits:10', $nimRule],
            'prodi' => 'required|in:Teknik Informatika,Teknik Multimedia dan Jaringan,Teknik Multimedia dan Digital',
            'kelas' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'first_choice_id' => 'required|exists:departmentsbiro,id,is_active,1',
            'second_choice_id' => 'nullable|different:first_choice_id|exists:departmentsbiro,id,is_active,1',
            'department_choice_reason' => 'required|string',
            'weakness_description' => 'required|string',
            'contribution_plan' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'instagram_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'youtube_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'political_statement' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'candidate_signature' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'parent_signature' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'educations' => 'nullable|array',
            'educations.*.education_type' => 'required_with:educations|in:formal,informal',
            'educations.*.school_name' => 'required_with:educations|string|max:255',
            'educations.*.start_year' => 'required_with:educations|integer|digits:4',
            'educations.*.end_year' => 'nullable|integer|digits:4',
            'educations.*.city' => 'required_with:educations|string|max:255',
            'educations.*.major' => 'nullable|string|max:255',
            'organizations' => 'nullable|array',
            'organizations.*.organization_name' => 'required_with:organizations|string|max:255',
            'organizations.*.start_year' => 'required_with:organizations|integer|digits:4',
            'organizations.*.end_year' => 'nullable|integer|digits:4',
            'organizations.*.place_or_institution' => 'required_with:organizations|string|max:255',
            'organizations.*.position' => 'required_with:organizations|string|max:255',
            'committees' => 'nullable|array',
            'committees.*.committee_name' => 'required_with:committees|string|max:255',
            'committees.*.start_year' => 'required_with:committees|integer|digits:4',
            'committees.*.end_year' => 'nullable|integer|digits:4',
            'committees.*.organizer' => 'required_with:committees|string|max:255',
            'committees.*.position' => 'required_with:committees|string|max:255',
            'skills' => 'nullable|array',
            'skills.*.skill_type' => 'required_with:skills|in:soft,hard',
            'skills.*.skill_name' => 'required_with:skills|string|max:255',
            'skills.*.proficiency' => 'required_with:skills|in:dasar,sedang,cakap',
            'facilities' => 'nullable|array',
            'facilities.*.facility_name' => 'required_with:facilities|string|max:255',
        ];
    }
}
